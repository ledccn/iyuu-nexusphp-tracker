<?php

namespace Ledc\Push\Pipelines;

use Ledc\Push\NotifyAdmin;
use support\Request;

/**
 * 后台管理频道，登录才能订阅
 */
class WebmanAdmin implements AuthorityPipelineInterface
{
    /**
     * @param Request $request
     * @param callable $next
     * @return bool
     */
    public static function process(Request $request, callable $next): bool
    {
        $channel_name = $request->post('channel_name');
        if (NotifyAdmin::CHANNEL_NAME === $channel_name) {
            return (bool)admin_id();
        }

        return $next($request);
    }
}
