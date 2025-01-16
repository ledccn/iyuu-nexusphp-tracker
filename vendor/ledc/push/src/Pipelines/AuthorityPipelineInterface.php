<?php

namespace Ledc\Push\Pipelines;

use support\Request;

/**
 * 私有频道鉴权
 * - 判断当前用户是否有权限监听channel_name
 */
interface AuthorityPipelineInterface
{
    /**
     * 契约方法
     * @param Request $request
     * @param callable $next
     * @return bool
     */
    public static function process(Request $request, callable $next): bool;
}
