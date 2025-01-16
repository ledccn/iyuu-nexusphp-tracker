<?php

namespace Ledc\Push;

/**
 * webman后台通知
 */
class NotifyAdmin
{
    /**
     * 频道名称
     */
    public const CHANNEL_NAME = 'private-webman-admin';

    /**
     * 成功消息
     * @param string $msg
     * @return void
     * @throws PushException
     */
    public static function success(string $msg): void
    {
        $data = [
            'type' => 'success',
            'msg' => $msg,
        ];
        Pusher::trigger(self::CHANNEL_NAME, 'notify', $data);
    }

    /**
     * 错误消息
     * @param string $msg
     * @return void
     * @throws PushException
     */
    public static function error(string $msg): void
    {
        $data = [
            'type' => 'error',
            'msg' => $msg,
        ];
        Pusher::trigger(self::CHANNEL_NAME, 'notify', $data);
    }

    /**
     * 警告消息
     * @param string $msg
     * @return void
     * @throws PushException
     */
    public static function warning(string $msg): void
    {
        $data = [
            'type' => 'warning',
            'msg' => $msg,
        ];
        Pusher::trigger(self::CHANNEL_NAME, 'notify', $data);
    }

    /**
     * 通用消息
     * @param string $msg
     * @return void
     * @throws PushException
     */
    public static function info(string $msg): void
    {
        $data = [
            'type' => 'info',
            'msg' => $msg,
        ];
        Pusher::trigger(self::CHANNEL_NAME, 'notify', $data);
    }

    /**
     * 进度条
     * @param string $type 进度条类型
     * @param int $success 成功数
     * @param int $fail 失败数
     * @param int $total 总共条数
     * @param array $args 其他参数
     * @return bool
     * @throws PushException
     */
    public static function progress(string $type, int $success, int $fail, int $total, array $args = []): bool
    {
        $data = [
            'type' => $type,
            'success' => $success,
            'fail' => $fail,
            'total' => $total,
            'args' => $args
        ];
        return Pusher::trigger(self::CHANNEL_NAME, 'progress', $data);
    }
}
