<?php

namespace Ledc\RedisQueue;

use Error;
use Exception;
use InvalidArgumentException;
use ReflectionClass;
use RuntimeException;
use Throwable;
use Webman\RedisQueue\Redis;

/**
 * 队列消费者助手
 * - 适用于webman/redis-queue
 */
trait HasHelper
{
    /**
     * 已编译的属性
     * @var array
     */
    private static array $compiledProperties = [];

    /**
     * 同步投递任务，异步执行
     * @param array $data
     * @param int $delay
     * @return bool
     */
    final public static function send(mixed $data, int $delay = 0): bool
    {
        try {
            $properties = self::getCompiledProperties();
            $queue = $properties['queue'];
            $connection = $properties['connection'];
            return Redis::connection($connection)->send($queue, $data, $delay);
        } catch (Error|Exception|Throwable $throwable) {
            throw new RuntimeException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * 获取当前类已编译的属性
     * @return array
     */
    private static function getCompiledProperties(): array
    {
        if (isset(self::$compiledProperties[static::class])) {
            return self::$compiledProperties[static::class];
        }

        $reflectionClass = new ReflectionClass(static::class);
        $properties = $reflectionClass->getDefaultProperties();
        $only = ['queue', 'connection'];
        foreach ($only as $field) {
            if (empty($properties[$field])) {
                throw new InvalidArgumentException("{$field}的值为空" . static::class);
            }
        }
        self::$compiledProperties[static::class] = $properties;
        return $properties;
    }
}
