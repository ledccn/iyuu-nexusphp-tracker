<?php

namespace plugin\tracker\app\services\chain;

use Closure;
use InvalidArgumentException;
use Iyuu\Tracker\Enums\ClientEventEnums;
use plugin\tracker\core\Chain;
use plugin\tracker\core\Rocket;
use support\Response;

/**
 * 检查端口黑名单
 */
class ChainPort implements Chain
{
    /**
     * 端口黑名单
     */
    public const array BLACK_LIST = [
        20, 21, 22,
        53,
        80, 81,
        411, 412, 413, 443,
        1214,
        3389,
        4662,
        6346, 6347, 6699, 6881, 6882, 6883, 6884, 6885, 6886, 6887,
        8080, 8081, 8443
    ];

    /**
     * @param Rocket $rocket
     * @param Closure $next
     * @return Response
     */
    public static function handle(Rocket $rocket, Closure $next): Response
    {
        $req = $rocket->getAnnounceRequest();
        $port = $req->port;

        if (in_array($port, static::BLACK_LIST, true)) {
            throw new InvalidArgumentException("Port $port is blacklisted.");
        }

        if (empty($port) && $req->getEventEnum() !== ClientEventEnums::stopped) {
            throw new InvalidArgumentException("Port 0 is invalid in event type.");
        }

        return $next($rocket);
    }
}
