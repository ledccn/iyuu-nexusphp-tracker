<?php

namespace Ledc\RedisQueue\Traits;

use Closure;
use support\Redis;

/**
 * Redis列表的入队、出队
 */
trait HasRedisList
{
    /**
     * 有序列表的key
     * @var string
     */
    protected string $key;

    /**
     * 【获取】有序列表的key
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * 【设置】有序列表的key
     * @param string $key
     * @return HasRedisList
     */
    public function setKey(string $key): static
    {
        $this->key = $key;
        return $this;
    }

    /**
     * 移除并获取列表的第一个元素.
     */
    public function pop(): array|bool
    {
        $json = Redis::lPop($this->getKey());

        return is_bool($json) ? $json : json_decode($json, true);
    }

    /**
     * 将值插入到列表的尾部(最右边).
     */
    public function push(array|Closure $data): bool|int
    {
        $data = $data instanceof Closure ? $data($this) : $data;

        // 将一个或多个值插入到列表的尾部(最右边)
        return Redis::rPush($this->getKey(), json_encode($data));
    }

    /**
     * 获取列表长度.
     */
    public function length(): int
    {
        return Redis::lLen($this->getKey()) ?: 0;
    }
}
