<?php

namespace plugin\tracker\core;

use Ledc\Webman\Traits\HasAttributes;

/**
 * 配置管理类
 */
class Repository
{
    use HasAttributes;

    /**
     * 最小汇报间隔
     */
    public const int MIN_ANNOUNCE_WAIT = 30;
}
