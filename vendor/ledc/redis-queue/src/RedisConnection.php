<?php

namespace Ledc\RedisQueue;

use RedisException;
use RuntimeException;
use Throwable;
use Workerman\Timer;
use Workerman\Worker;

/**
 * Redis
 */
class RedisConnection extends \Redis
{
    /**
     * @var array
     */
    protected array $config = [];

    /**
     * @param array $config
     * @return void
     * @throws RedisException
     */
    public function connectWithConfig(array $config = []): void
    {
        static $timer;
        if ($config) {
            $this->config = $config;
        }
        if (false === $this->connect($this->config['host'], $this->config['port'], $this->config['timeout'] ?? 2)) {
            throw new RuntimeException("Redis connect {$this->config['host']}:{$this->config['port']} fail.");
        }
        if (!empty($this->config['auth'])) {
            $this->auth($this->config['auth']);
        }
        if (!empty($this->config['db'])) {
            $this->select($this->config['db']);
        }
        if (!empty($this->config['prefix'])) {
            $this->setOption(\Redis::OPT_PREFIX, $this->config['prefix']);
        }
        if (Worker::getAllWorkers() && !$timer) {
            $timer = Timer::add($this->config['ping'] ?? 55, function ()  {
                $this->execCommand('ping');
            });
        }
    }

    /**
     * @param string $command
     * @param ...$args
     * @return mixed
     * @throws Throwable
     */
    protected function execCommand(string $command, ...$args): mixed
    {
        try {
            return $this->{$command}(...$args);
        } catch (Throwable $e) {
            $msg = strtolower($e->getMessage());
            if ($msg === 'connection lost' || strpos($msg, 'went away')) {
                $this->connectWithConfig();
                return $this->{$command}(...$args);
            }
            throw $e;
        }
    }

    /**
     * 投递消息(同步)
     * @param string $queue 队列名
     * @param mixed $data 数据(可以直接传数组，无需序列化)
     * @param int $delay 投递延迟消息
     * @return bool
     * @throws Throwable
     */
    public function send(string $queue, mixed $data, int $delay = 0): bool
    {
        $delay = max(0, $delay);
        $queue_waiting = '{redis-queue}-waiting';
        $queue_delay = '{redis-queue}-delayed';
        $now = time();
        $package_str = json_encode([
            'id' => time() . rand(),
            'time' => $now,
            'delay' => $delay,
            'attempts' => 0,
            'queue' => $queue,
            'data' => $data
        ]);
        if ($delay) {
            return (bool)$this->execCommand('zAdd', $queue_delay, $now + $delay, $package_str);
        }
        return (bool)$this->execCommand('lPush', $queue_waiting . $queue, $package_str);
    }
}
