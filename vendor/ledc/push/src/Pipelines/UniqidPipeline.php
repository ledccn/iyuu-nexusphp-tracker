<?php

namespace Ledc\Push\Pipelines;

use Exception;
use Ledc\Push\UniqidChannel;
use support\Redis;
use support\Request;

/**
 * 鉴权：唯一值的私有频道
 */
class UniqidPipeline implements AuthorityPipelineInterface
{
    /**
     * @param Request $request
     * @param callable $next
     * @return bool
     * @throws Exception
     */
    public static function process(Request $request, callable $next): bool
    {
        $channel_name = $request->post('channel_name');
        if (UniqidChannel::when($channel_name)) {
            $limitIp = UniqidChannel::PREFIX . $request->getRealIp();
            if (Redis::exists($limitIp)) {
                return false;
            }

            $has_authority = (new UniqidChannel($channel_name))->valid();
            if (false === $has_authority) {
                Redis::setEx($limitIp, 10, $channel_name);
            }
            return $has_authority;
        }

        return $next($request);
    }
}
