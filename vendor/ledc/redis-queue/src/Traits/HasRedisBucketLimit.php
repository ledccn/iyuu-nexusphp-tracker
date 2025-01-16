<?php

namespace Ledc\RedisQueue\Traits;

use support\Redis;

/**
 * Redis实现的令牌桶
 */
trait HasRedisBucketLimit
{
    /**
     * 令牌桶Key
     */
    protected string $bucketKey;

    /**
     * 桶内令牌上限
     */
    protected int $bucketMaxLimit = 10;

    /**
     * 【获取】令牌桶Key
     * @return string
     */
    public function getBucketKey(): string
    {
        return $this->bucketKey;
    }

    /**
     * 【获取】桶内令牌上限
     * @return int
     */
    public function getBucketMaxLimit(): int
    {
        return $this->bucketMaxLimit;
    }

    /**
     * 【设置】令牌桶Key
     * @param string $bucketKey
     * @return HasRedisBucketLimit
     */
    public function setBucketKey(string $bucketKey): static
    {
        $this->bucketKey = $bucketKey;
        return $this;
    }

    /**
     * 【设置】桶内令牌上限
     * @param int $bucketMaxLimit
     * @return HasRedisBucketLimit
     */
    public function setBucketMaxLimit(int $bucketMaxLimit): static
    {
        $this->bucketMaxLimit = $bucketMaxLimit;
        return $this;
    }

    /**
     * 获取令牌
     */
    public function getToken(): bool
    {
        return (bool)Redis::lPop($this->getBucketKey());
    }

    /**
     * 重置令牌桶（加满）
     */
    public function resetToken(): void
    {
        $this->addToken($this->getBucketMaxLimit());
    }

    /**
     * 添加令牌
     * @param int $num 数量
     * @return int 实际加入的数量
     */
    public function addToken(int $num): int
    {
        $num = max(0, $num);
        $diff = $this->getBucketMaxLimit() - $this->lengthToken();
        $num = min($diff, $num);
        if (0 < $num) {
            $token = array_fill(0, $num, 1);
            Redis::rPush($this->getBucketKey(), ...$token);
        }

        return $num;
    }

    /**
     * 桶内令牌个数
     */
    public function lengthToken(): int
    {
        return Redis::lLen($this->getBucketKey()) ?: 0;
    }
}
