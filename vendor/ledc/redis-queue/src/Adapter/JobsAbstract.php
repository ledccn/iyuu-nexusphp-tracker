<?php

namespace Ledc\RedisQueue\Adapter;

use RuntimeException;

/**
 * Redis异步任务抽象类
 */
abstract class JobsAbstract
{
    /**
     * 抽象方法
     * @return void
     */
    abstract public function execute(): void;

    /**
     * 异步调用当前类的execute方法
     * @param null|int|string|bool|array $args 数据参数
     * @param int $delay 延时的秒数
     * @param array $constructor 构造函数参数
     * @return bool
     */
    final public static function dispatch(mixed $args = null, int $delay = 0, array $constructor = []): bool
    {
        $payload = [
            'job' => static::class . '@execute',
            'args' => $args,
            'constructor' => $constructor,
        ];

        return JobsConsumer::send($payload, $delay);
    }

    /**
     * 调用任意类的公共方法
     * @param array $callable 可调用数组
     * @param array|bool|int|string|null $args 数据参数
     * @param int $delay 延时的秒数
     * @param array $constructor 构造函数参数
     * @return bool
     */
    final public static function emit(array $callable, mixed $args = null, int $delay = 0, array $constructor = []): bool
    {
        if (2 !== count($callable)) {
            throw new RuntimeException('参数callable错误');
        }
        list($class, $action) = $callable;
        if (!method_exists($class, $action)) {
            throw new RuntimeException($class . '不存在方法 ' . $action);
        }

        $payload = [
            'job' => $class . '@' . $action,
            'args' => $args,
            'constructor' => $constructor,
        ];

        return JobsConsumer::send($payload, $delay);
    }
}
