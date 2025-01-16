<?php

namespace Ledc\Push;

use Random\RandomException;
use support\Redis;

/**
 * 唯一值的私有频道
 */
class UniqidChannel
{
    /**
     * 存储在Session的键名
     */
    public const SESSION_KEY = 'private-uniqid-channel';

    /**
     * 私有频道的前缀
     */
    public const PREFIX = 'private-uniqid-';

    /**
     * 私有频道的完整名称
     * @var string
     */
    protected string $channel_name = '';

    /**
     * 构造函数
     * @param string $channel_name 私有频道的完整名称
     */
    public function __construct(string $channel_name = '')
    {
        if (static::when($channel_name)) {
            $this->channel_name = $channel_name;
        }
    }

    /**
     * 前缀符合要求
     * @param string $channel_name
     * @return bool
     */
    public static function when(string $channel_name): bool
    {
        return str_starts_with($channel_name, self::PREFIX);
    }

    /**
     * 判断频道名是否有效
     * @return bool
     */
    public function valid(): bool
    {
        $session = request()->session();
        if ($session->has(static::SESSION_KEY)) {
            return $session->get(static::SESSION_KEY) === $this->channel_name;
        }
        return (bool)Redis::exists($this->getCacheKey());
    }

    /**
     * 生成并重置channel_name
     * @return string
     * @throws RandomException
     */
    public function generate(): string
    {
        $this->channel_name = self::PREFIX . bin2hex(pack('d', microtime(true)) . random_bytes(12));
        request()->session()->set(static::SESSION_KEY, $this->channel_name);
        return $this->channel_name;
    }

    /**
     * redis缓存key
     * @return string
     */
    protected function getCacheKey(): string
    {
        return static::SESSION_KEY . ':' . $this->channel_name;
    }

    /**
     * 保存到redis
     * @param int $ttl
     * @return void
     */
    public function saveToRedis(int $ttl = 120): void
    {
        Redis::setEx($this->getCacheKey(), max(120, $ttl), $this->channel_name);
    }
}
