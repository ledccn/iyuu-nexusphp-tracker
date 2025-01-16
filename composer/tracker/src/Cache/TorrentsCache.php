<?php

namespace Iyuu\Tracker\Cache;

use Ledc\Container\App;
use Ledc\RedisQueue\Traits\HasRedisHash;

/**
 * 种子缓存
 * - 数据类型 映射关系：info_hash => torrent(json)
 */
readonly class TorrentsCache
{
    use HasRedisHash;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->setKeyName(__CLASS__);
    }

    /**
     * @return static
     */
    public static function instance(): static
    {
        return App::pull(static::class);
    }
}
