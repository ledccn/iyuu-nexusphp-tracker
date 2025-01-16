<?php

namespace plugin\tracker\app\services\chain;

use Closure;
use plugin\tracker\core\Chain;
use plugin\tracker\core\Rocket;
use support\Response;
use function Iyuu\Tracker\bencode;

/**
 * 检查重复请求&缓存请求结果
 */
class ChainAnnounceLimit implements Chain
{
    /**
     * @param Rocket $rocket
     * @param Closure $next
     * @return Response
     */
    public static function handle(Rocket $rocket, Closure $next): Response
    {
        $req = $rocket->getAnnounceRequest();
        // 重复汇报返回空peers
        if (!$req->limitFullUrl()) {
            return bencode($rocket->emptyResponseDict());
        }

        // 检查汇报是否频繁
        if (!$req->limitIdentity()) {
            return bencode($rocket->warningResponseDict('Request too frequent(h)', 300));
        }

        // 缓存响应
        // TODO...

        return $next($rocket);
    }
}
