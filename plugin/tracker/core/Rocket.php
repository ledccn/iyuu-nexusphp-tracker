<?php

namespace plugin\tracker\core;

use plugin\tracker\app\model\Torrent;

/**
 * 承载有效请求数据
 */
class Rocket
{
    /**
     * 解析后的请求数据
     * @var AnnounceRequest
     */
    protected AnnounceRequest $announceRequest;

    /**
     * 用户守卫
     * @var Guard
     */
    protected Guard $guard;

    /**
     * 响应字典
     * @var Dict
     */
    protected Dict $dict;

    /**
     * 当前会话的种子
     * @var Torrent|null
     */
    protected ?Torrent $torrent = null;

    /**
     * 构造函数
     */
    public function __construct(AnnounceRequest $announceRequest, Guard $guard)
    {
        $this->announceRequest = $announceRequest;
        $this->guard = $guard;
        $this->dict = new Dict([]);
    }

    /**
     * 【获取】解析后的请求数据
     * @return AnnounceRequest
     */
    public function getAnnounceRequest(): AnnounceRequest
    {
        return $this->announceRequest;
    }

    /**
     * 【获取】用户守卫
     * @return Guard
     */
    public function getGuard(): Guard
    {
        return $this->guard;
    }

    /**
     * 【获取】字典
     * @return Dict
     */
    public function getDict(): Dict
    {
        return $this->dict;
    }

    /**
     * 【获取】当前种子
     * @return Torrent|null
     */
    public function getTorrent(): ?Torrent
    {
        return $this->torrent;
    }

    /**
     * 【设置】字典
     * @param array|Dict $dict
     */
    public function setDict(array|Dict $dict): void
    {
        $this->dict = $dict instanceof Dict ? $dict : new Dict($dict);
    }

    /**
     * 【设置】当前种子
     * @param Torrent $torrent
     */
    public function setTorrent(Torrent $torrent): void
    {
        $this->torrent = $torrent;
    }

    /**
     * 默认的响应字典
     * @return array
     */
    public function defaultResponseDict(): array
    {
        if ($this->getDict()->isEmpty()) {
            $dict = [
                'interval' => 300,
                'min interval' => 300,
                'complete' => intval($this->getTorrent()?->seeders ?? 0),
                'incomplete' => intval($this->getTorrent()?->leechers ?? 0),
                'peers' => [],
            ];

            if ($this->getAnnounceRequest()->compact) {
                $dict['peers'] = '';  // Change `peers` from array to string
                $dict['peers6'] = '';   // If peer use IPv6 address , we should add packed string in `peers6`
            }
            return $dict;
        } else {
            return $this->getDict()->toArray();
        }
    }

    /**
     * 空peer响应
     * @return array
     */
    public function emptyResponseDict(): array
    {
        $interval = 300 + mt_rand(1, 10);
        $dict = [
            'interval' => $interval,
            'min interval' => $interval,
            'peers' => [],
        ];

        if ($this->getAnnounceRequest()->compact) {
            $dict['peers'] = '';  // Change `peers` from array to string
            $dict['peers6'] = '';   // If peer use IPv6 address , we should add packed string in `peers6`
        }
        return $dict;
    }

    /**
     * 警告响应
     * @param string $message
     * @param int $interval
     * @return array
     */
    public function warningResponseDict(string $message, int $interval = 7200): array
    {
        $dict = $this->defaultResponseDict();
        $dict['warning message'] = $message;
        if (0 < $interval) {
            $dict['interval'] = $interval;
            $dict['min interval'] = $interval;
        }

        return $dict;
    }
}
