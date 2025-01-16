<?php

namespace plugin\tracker\app\services\chain;

use Closure;
use plugin\tracker\core\Chain;
use plugin\tracker\core\Rocket;
use support\Response;

/**
 * 校验Peer规则
 */
class ChainPeerRules implements Chain
{
    /**
     * @param Rocket $rocket
     * @param Closure $next
     * @return Response
     */
    public static function handle(Rocket $rocket, Closure $next): Response
    {
        return $next($rocket);
    }
}
