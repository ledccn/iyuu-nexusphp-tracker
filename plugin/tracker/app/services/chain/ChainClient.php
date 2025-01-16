<?php

namespace plugin\tracker\app\services\chain;

use Closure;
use plugin\tracker\core\Chain;
use plugin\tracker\core\Rocket;
use support\Response;

/**
 * 检查客户端版本
 */
class ChainClient implements Chain
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
