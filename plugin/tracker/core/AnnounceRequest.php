<?php

namespace plugin\tracker\core;

use Iyuu\Tracker\Enums\ClientEventEnums;
use Iyuu\Tracker\Utils;
use Ledc\Macroable\Macroable;
use Ledc\Webman\Traits\HasAttributes;
use function is_numeric;
use function Ledc\WebmanUser\redis_set_nx;
use function strpos;
use function substr;
use function urldecode;

/**
 * 请求参数（已经格式化之后）
 * @property string $info_hash 种子哈希20字节sha1
 * @property string $peer_id 下载器唯一ID
 * @property string $ip IP地址
 * @property int $port 端口
 * @property int $downloaded 十进制下载量
 * @property int $uploaded 十进制上传量
 * @property int $left 十进制仍需下载的字节数
 * @property string $event 客户端的种子事件
 * @property bool $compact 是否紧凑型响应(BEP0023)
 * @property bool $no_peer_id 是否返回peer_id
 * @property int $numwant 客户端希望返回的Peer数量
 * @property string $ipv4 IPv4地址
 * @property string $ipv6 IPv6地址
 * @property string $corrupt 客户端标识
 * @property string $key 客户端标识
 * @property string $authkey 用户身份标识
 * @property string $passkey 用户身份标识
 */
class AnnounceRequest
{
    use HasAttributes, Macroable;

    /**
     * 定义宏指令方法名称，作用：获取请求客户端IP
     */
    public const string MACRO_GET_REMOTE_IP = 'getRemoteIp';

    /**
     * 构造函数
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
        $this->initAttributes();
        $this->checkMissingKeys();
    }

    /**
     * 获取客户端事件枚举值
     * @return ClientEventEnums|null
     */
    public function getEventEnum(): ?ClientEventEnums
    {
        return ClientEventEnums::tryFrom($this->event);
    }

    /**
     * 初始化属性
     */
    protected function initAttributes(): void
    {
        // 初始化ip、端口（依据请求参数）
        $ip = $this->ip;
        $ipv4 = $this->ipv4;
        $ipv6 = $this->ipv6;
        if ($ip || $ipv4 || $ipv6) {
            // 确定IPV4
            $this->ipv4 = Utils::isValidIPv4($ip) && ($ip !== $ipv4) ? $ip : (Utils::isValidIPv4($ipv4) ? $ipv4 : '');
            // ipv6参数为空
            if (empty($ipv6)) {
                // 是否IPV6
                $this->ipv6 = Utils::isValidIPv6($ip) ? $ip : '';
            } else {
                // 单独处理ipv6参数
                if (!Utils::isValidIPv6($ipv6)) {
                    $this->ipv6Endpoint($ipv6);
                }
            }
        }

        // 初始化ip（依据请求头）
        $header_ip = $this->getRemoteClientIp();
        if (empty($this->ip) && Utils::isValidIP($header_ip)) {
            $this->ip = $header_ip;
        }

        if (empty($this->ipv4) && Utils::isValidIPv4($header_ip)) {
            $this->ipv4 = $header_ip;
        }

        if (empty($this->ipv6) && Utils::isValidIPv6($header_ip)) {
            $this->ipv6 = $header_ip;
        }

        // 初始化客户端希望返回的Peer数量
        $this->numwant = (int)min(max($this->numwant, 50), 200);
    }

    /**
     * 从请求头获取客户端IP地址
     * - 由于HTTP头很容伪造，所以此方法获得的客户端IP并非100%可信，尤其是$safe_mode为false时。
     * - 透过代理获得客户端真实IP的比较可靠的方法是，已知安全的代理服务器IP，并且明确知道携带真实IP是哪个HTTP头；
     * - 如果$request->getRemoteIp()返回的IP确认为已知的安全的代理服务器，然后通过$request->header('携带真实IP的HTTP头')获取真实IP。
     * @param bool $safeMode
     * @return string
     */
    public function getRemoteClientIp(bool $safeMode = true): string
    {
        // 请综合考虑CDN、Nginx代理、防火墙等情况
        if (static::hasMacro(self::MACRO_GET_REMOTE_IP)) {
            $method = static::MACRO_GET_REMOTE_IP;
            return static::$method($safeMode);
        }

        return request()->getRealIp($safeMode);
    }

    /**
     * Example announce string with [2001::53aa:64c:0:7f83:bc43:dec9]:6882 as IPv6 endpoint:
     * GET /announce?peer_id=aaaaaaaaaaaaaaaaaaaa&info_hash=aaaaaaaaaaaaaaaaaaaa&port=6881&left=0&downloaded=100&uploaded=0&compact=1&ipv6=%5B2001%3A%3A53Aa%3A64c%3A0%3A7f83%3Abc43%3Adec9%5D%3A6882
     * @param string $ipv6
     */
    private function ipv6Endpoint(string $ipv6): void
    {
        // Example $ipv6 = '%5B2001%3A%3A53Aa%3A64c%3A0%3A7f83%3Abc43%3Adec9%5D%3A6882';
        if (Utils::isUrlEncoded($ipv6)) {
            $ipv6 = urldecode($ipv6);
        }
        // Example $ipv6 = '[2001::53Aa:64c:0:7f83:bc43:dec9]:6882';
        $pos = strpos($ipv6, ']:');
        if ($pos) {
            $ip = substr($ipv6, 1, $pos - 1);
            $port = substr($ipv6, \strrpos($ipv6, ':') + 1);
            if (is_numeric($port) && ($this->port != $port)) {
                $this->port = (int)$port;
            }
        } else {
            $ip = $ipv6;
        }
        $this->ipv6 = Utils::isValidIPv6($ip) ? $ip : '';
    }

    /**
     * 限流规则：本次请求的唯一哈希值
     * - 计算完整URL(不含协议部分)的sha1值
     * @param int $ttl
     * @return bool
     */
    public function limitFullUrl(int $ttl = 60): bool
    {
        return redis_set_nx(static::fullUrlSHA1(), time(), $ttl);
    }

    /**
     * @param int $ttl
     * @return bool
     */
    public function limitIdentity(int $ttl = 60): bool
    {
        return redis_set_nx($this->identity(), time(), $ttl);
    }

    /**
     * 本次请求的唯一哈希值
     * - 计算完整URL(不含协议部分)的sha1值
     * @return string
     */
    protected static function fullUrlSHA1(): string
    {
        return __CLASS__ . ':fullUrlSHA1:' . sha1(request()->fullUrl());
    }

    /**
     * 本次请求的身份凭证
     * - 以6个参数，生成唯一的身份识别码
     * @return string
     */
    protected function identity(): string
    {
        return __CLASS__ . ':identity:' . md5(implode(':', [
                $this->passkey, // User
                $this->info_hash, // Torrent
                $this->port, // Port
                $this->peer_id, $this->key, // Peer
                $this->event, // Client Event
            ]));
    }

    /**
     * 是否为播种者身份
     * - set if seeder based on left field
     * @return bool
     */
    public function isSeeder(): bool
    {
        return 0 === $this->left;
    }
}
