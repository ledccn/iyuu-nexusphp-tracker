<?php

namespace Iyuu\Tracker\Enums;

/**
 * 下载器客户端事件
 */
enum ClientEventEnums: string
{
    /**
     * 开始
     */
    case started = 'started';
    /**
     * 完成
     */
    case completed = 'completed';
    /**
     * 停止
     */
    case stopped = 'stopped';
    /**
     * 暂停
     */
    case paused = 'paused';
}
