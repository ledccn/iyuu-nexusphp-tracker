<?php

namespace Ledc\Push;

/**
 * 发送调度通知
 */
class NotifyDispatcher
{
    /**
     * 频道名称
     */
    public const CHANNEL_NAME = 'dispatcher';

    /**
     * 发送调度通知
     * @param int|string $type 任务主类型
     * @param int|string $subType 任务子类型
     * @param int $length 任务队列长度
     * @return void
     * @throws PushException
     */
    public static function send(int|string $type, int|string $subType, int $length): void
    {
        $data = [
            'type' => $type,
            'subtype' => $subType,
            'length' => $length
        ];
        Pusher::trigger(self::CHANNEL_NAME, 'notify', $data);
    }
}
