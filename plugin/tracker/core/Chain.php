<?php

namespace plugin\tracker\core;

use Closure;
use support\Response;

/**
 * 责任链
 */
interface Chain
{
    /**
     * @param Rocket $rocket
     * @param Closure $next
     * @return Response
     */
    public static function handle(Rocket $rocket, Closure $next): Response;
}
