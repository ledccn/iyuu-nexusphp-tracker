<?php

namespace Iyuu\Tracker\Cache;

use Ledc\Container\App;
use Ledc\RedisQueue\Traits\HasRedisSortedSet;

/**
 * 无效的passkey缓存
 * - 数据类型 映射关系：timestamp => passkey
 */
readonly class PasskeyInvalidCache
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
