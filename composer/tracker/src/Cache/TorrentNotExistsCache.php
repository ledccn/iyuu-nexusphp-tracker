<?php

namespace Iyuu\Tracker\Cache;

use Ledc\Container\App;
use Ledc\RedisQueue\Traits\HasRedisSortedSet;

/**
 * 缓存不存在的种子
 * - 数据类型 映射关系：timestamp => info_hash
 */
readonly class TorrentNotExistsCache
{
    use HasRedisSortedSet;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->setSortedSetKey(__CLASS__);
    }

    /**
     * @return static
     */
    public static function instance(): static
    {
        return App::pull(static::class);
    }
}
