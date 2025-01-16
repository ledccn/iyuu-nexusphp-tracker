<?php

namespace Ledc\RedisQueue\Adapter;

use Ledc\RedisQueue\HasHelper;
use support\Container;
use Webman\RedisQueue\Consumer;

/**
 * 异步任务队列
 */
class JobsConsumer implements Consumer
{
    use HasHelper;

    /**
     * 要消费的队列名
     * @var string
     */
    public string $queue = 'jobs_queue_async_consumer';
    /**
     * 连接名
     * - 对应 config/redis-queue.php 里的连接
     * - 对应 plugin/webman/redis-queue/redis.php 里的连接
     * @return string
     */
    public string $connection = 'default';

    /**
     * 消费方法
     * - 消费过程中没有抛出异常和Error视为消费成功；否则消费失败,进入重试队列
     * @param array $data 数据
     */
    final public function consume($data): void
    {
        $job = $data['job'] ?? '';
        $parameters = $data['args'] ?? null;
        $constructor = $data['constructor'] ?? [];
        if (empty($job)) {
            return;
        }

        list($class, $method) = self::parseJob($job);
        $instance = $constructor ? Container::make($class, $constructor) : Container::get($class);
        if (method_exists($instance, $method)) {
            if (is_array($parameters)) {
                if ($parameters) {
                    // 数组，支持命名参数
                    call_user_func_array([$instance, $method], $parameters);
                } else {
                    // 空数组
                    $instance->{$method}();
                }
            } else {
                // null/int/bool/string
                $instance->{$method}($parameters);
            }
        }
    }

    /**
     * 将job解析为类和方法
     * @param string $job
     * @return array
     */
    final protected static function parseJob(string $job): array
    {
        $segments = explode('@', $job);
        return 2 === count($segments) ? $segments : [$segments[0], 'execute'];
    }
}
