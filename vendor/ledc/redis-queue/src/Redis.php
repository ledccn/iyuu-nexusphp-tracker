<?php

namespace Ledc\RedisQueue;

use RedisException;
use RuntimeException;

/**
 * Redis客户端（同步）
 *  - 列表用法（先进先出）：rPush + lPop + lLen
 *  - 锁定EX秒：SET lock_key unique_value NX EX 10
 *  - 锁定PX毫秒：SET lock_key unique_value NX PX 10000
 *
 * @link https://www.workerman.net/plugin/12
 * @link https://www.workerman.net/doc/workerman/components/workerman-redis-queue.html
 * @link https://www.workerman.net/doc/workerman/components/workerman-redis.html
 * @method static bool send(string $queue, mixed $data, int $delay = 0) 投递消息(同步)
 * @method static false|string lPop($key) 移除并获取列表的第一个元素（左边）
 * @method static false|string rPop($key) 移除并获取列表的最后一个元素（最右边）
 * @method static false|int lPush($key, ...$entries) 将一个或多个值插入到列表头部（左边）
 * @method static false|int rPush($key, ...$entries) 将一个或多个值插入到列表尾部（最右边）
 * @method static false|int lLen($key) 获取列表长度
 */
class Redis
{
    /**
     * @var RedisConnection[]
     */
    protected static array $_connections = [];

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws RedisException
     */
    public static function __callStatic(string $name, array $arguments)
    {
        return static::connection('default')->{$name}(... $arguments);
    }

    /**
     * @param string $name
     * @return RedisConnection
     * @throws RedisException
     */
    public static function connection(string $name = 'default'): RedisConnection
    {
        if (!isset(static::$_connections[$name])) {
            $configs = config('redis_queue', config('plugin.webman.redis-queue.redis', []));
            if (!isset($configs[$name])) {
                throw new RuntimeException("RedisQueue connection $name not found");
            }
            $config = $configs[$name];
            static::$_connections[$name] = static::connect($config);
        }
        return static::$_connections[$name];
    }

    /**
     * 连接Redis服务
     * @param array $config
     * @return RedisConnection
     * @throws RedisException
     */
    protected static function connect(array $config): RedisConnection
    {
        if (!extension_loaded('redis')) {
            throw new RuntimeException('Please make sure the PHP Redis extension is installed and enabled.');
        }

        $redis = new RedisConnection();
        $address = $config['host'];
        $config = [
            'host' => parse_url($address, PHP_URL_HOST),
            'port' => parse_url($address, PHP_URL_PORT),
            'db' => $config['options']['database'] ?? $config['options']['db'] ?? 0,
            'auth' => $config['options']['auth'] ?? '',
            'timeout' => $config['options']['timeout'] ?? 2,
            'ping' => $config['options']['ping'] ?? 55,
            'prefix' => $config['options']['prefix'] ?? '',
        ];
        $redis->connectWithConfig($config);
        return $redis;
    }
}
