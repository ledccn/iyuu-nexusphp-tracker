<?php

namespace Ledc\Push;

use Ledc\Pipeline\Pipeline;
use Ledc\Push\Pipelines\UniqidPipeline;
use Ledc\Push\Pipelines\WebmanAdmin;
use RuntimeException;
use support\Request;
use Throwable;

/**
 * PushApi调用类
 */
class Pusher
{
    /**
     * @var Api|null
     */
    protected static ?Api $api = null;

    /**
     * 触发客户端事件
     *  - 在一个或多个通道上触发事件
     *  - 通过提供通道名称和有效负载来触发事件
     *  - 可提供发件人的socketId以排除客户端
     * @param array|string $channels 将要发布事件的通道
     * @param string $event 事件名称
     * @param mixed $data 事件数据
     * @param string|null $socket_id [可选]发件人的socketId
     * @return bool
     * @throws PushException
     */
    public static function trigger(array|string $channels, string $event, mixed $data, string $socket_id = null): bool
    {
        return static::api()->trigger($channels, $event, $data, $socket_id);
    }

    /**
     * @return Api
     */
    public static function api(): Api
    {
        if (null === static::$api) {
            try {
                // webman下可以直接使用config获取配置，非webman环境需要手动写入相应配置
                static::$api = new Api(
                    str_replace('0.0.0.0', '127.0.0.1', config('plugin.ledc.push.app.api')),
                    config('plugin.ledc.push.app.app_key'),
                    config('plugin.ledc.push.app.app_secret')
                );
            } catch (PushException $exception) {
                throw new RuntimeException($exception->getMessage());
            }
        }
        return static::$api;
    }

    /**
     * 判断当前用户是否有权限监听channel_name
     * @param Request $request
     * @return bool
     */
    public static function hasAuthority(Request $request): bool
    {
        try {
            $pipeline = new Pipeline();
            return $pipeline->send($request)
                ->through(config('plugin.ledc.push.app.pipeline', [
                    [WebmanAdmin::class, 'process'],
                    [UniqidPipeline::class, 'process'],
                ]))
                ->then(function (Request $request) {
                    return false;
                });
        } catch (Throwable $throwable) {
            return false;
        }
    }
}
