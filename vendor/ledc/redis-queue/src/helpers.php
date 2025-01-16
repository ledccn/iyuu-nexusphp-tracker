<?php

namespace Ledc\RedisQueue;

use Ledc\RedisQueue\Adapter\JobsAbstract;

/**
 * 【异步】调用任意类的公共方法
 * @param array $callable 可调用数组
 * @param array|bool|int|string|null $args 数据参数
 * @param int $delay 延时的秒数
 * @param array $constructor 构造函数参数
 * @return bool
 */
function job_emit(array $callable, mixed $args = null, int $delay = 0, array $constructor = []): bool
{
    return JobsAbstract::emit($callable, $args, $delay, $constructor);
}
