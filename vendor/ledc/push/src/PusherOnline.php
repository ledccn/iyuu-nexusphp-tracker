<?php

namespace Ledc\Push;

use support\Redis;

/**
 * Pusher在线频道
 */
class PusherOnline
{
    /**
     * 集合的key
     * @return string
     */
    public static function key(): string
    {
        return 'PusherOnlineChannels:' . config('plugin.ledc.push.app.app_key', 'empty_app_key');
    }

    /**
     * 清理在线频道集合
     * @return void
     */
    public static function clear(): void
    {
        Redis::del(self::key());
    }

    /**
     * 在线频道的总数
     * @return int
     */
    public static function sCard(): int
    {
        return Redis::sCard(self::key());
    }

    /**
     * 在线频道的总数
     * @return int
     */
    public static function count(): int
    {
        return Redis::sCard(self::key());
    }

    /**
     * 把上线的频道加入集合
     * @param string $channel_name 频道名称
     * @return int
     */
    public static function sAdd(string $channel_name): int
    {
        return Redis::sAdd(self::key(), $channel_name);
    }

    /**
     * 把下线的频道移出集合
     * @param string $channel_name 频道名称
     * @return int
     */
    public static function sRem(string $channel_name): int
    {
        return Redis::sRem(self::key(), $channel_name);
    }

    /**
     * 判断频道名是否在集合内
     * @param string $channel_name 频道名称
     * @return bool
     */
    public static function sIsMember(string $channel_name): bool
    {
        return Redis::sIsMember(self::key(), $channel_name);
    }
}
