<?php

namespace Ledc\RedisQueue\Traits;

use support\Redis;

/**
 * Redis有序集合
 */
trait HasRedisSortedSet
{
    /**
     * 有序集合的key
     * @var string
     */
    protected string $sortedSetKey;

    /**
     * 【获取】有序集合的key
     * @return string
     */
    public function getSortedSetKey(): string
    {
        return $this->sortedSetKey;
    }

    /**
     * 【设置】有序集合的key
     * @param string $sortedSetKey
     * @return HasRedisSortedSet
     */
    public function setSortedSetKey(string $sortedSetKey): static
    {
        $this->sortedSetKey = $sortedSetKey;
        return $this;
    }

    /**
     * 向有序集合添加一个或多个成员，或者更新已存在成员的分数
     * @param int|string $score int或double的分数
     * @param string $member
     * @return int|string
     */
    public function zAdd(int|string $score, string $member): int|string
    {
        return Redis::zAdd($this->getSortedSetKey(), $score, $member);
    }

    /**
     * 有序集合中对指定成员的分数加上增量 increment
     * @param int|string $value
     * @param string $member
     * @return int|string
     */
    public function zIncrBy(int|string $value, string $member): int|string
    {
        return Redis::zIncrBy($this->getSortedSetKey(), $value, $member);
    }

    /**
     * 移除有序集合中的一个或多个成员
     * @param string $member
     * @return int
     */
    public function zRem(string $member): int
    {
        return Redis::zRem($this->getSortedSetKey(), $member);
    }

    /**
     * 获取有序集合的成员数
     * @return int
     */
    public function zCard(): int
    {
        return Redis::zCard($this->getSortedSetKey());
    }

    /**
     * 返回有序集合中指定成员的索引(索引从0开始)
     * @param string $member
     * @return int|bool 不存在返回false
     */
    public function zRank(string $member): int|bool
    {
        return Redis::zRank($this->getSortedSetKey(), $member);
    }

    /**
     * 返回有序集中，成员的分数值
     * @param string $member
     * @return float|bool 不存在返回false
     */
    public function zScore(string $member): float|bool
    {
        return Redis::zScore($this->getSortedSetKey(), $member);
    }

    /**
     * 计算在有序集合中指定区间分数的成员数
     * @param string $start
     * @param string $end
     * @return int|null
     */
    public function zCount(string $start, string $end): ?int
    {
        return Redis::zCount($this->getSortedSetKey(), $start, $end);
    }

    /**
     * 通过索引区间返回有序集合指定区间内的成员
     * @param string $start
     * @param string $stop
     * @param string $by
     * @param string $rev
     * @param array $options
     * @return array
     * @link https://redis.io/commands/zrange/
     */
    public function zRange(string $start, string $stop, string $by = 'BYSCORE', string $rev = 'REV', array $options = ['LIMIT', 0, 128]): array
    {
        return Redis::zRange($this->getSortedSetKey(), ... func_get_args());
    }

    /**
     * 返回有序集中指定分数区间内的成员
     * - 有序集成员按分数值递减(从大到小)的次序排列
     * @param string $start
     * @param string $end
     * @param array $options
     * @return array
     */
    public function zRevRangeByScore(string $start, string $end = '-inf', array $options = ['LIMIT', 0, 128]): array
    {
        return Redis::zRevRangeByScore($this->getSortedSetKey(), $start, $end, $options);
    }

    /**
     * 通过分数返回有序集合指定区间内的成员
     * - 有序集成员按分数值递增(从小到大)次序排列
     * @param string $min
     * @param string $max
     * @param array $options
     * @return array
     */
    public function zRangeByScore(string $min = '-inf', string $max = '+inf', array $options = ['LIMIT', 0, 128]): array
    {
        return Redis::zRangeByScore($this->getSortedSetKey(), $min, $max, $options);
    }
}
