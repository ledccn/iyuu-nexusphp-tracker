<?php

namespace plugin\tracker\app\services;

use Ledc\Pipeline\Pipeline;
use plugin\tracker\app\services\chain\ChainAnnounceLimit;
use plugin\tracker\app\services\chain\ChainClient;
use plugin\tracker\app\services\chain\ChainInitTorrent;
use plugin\tracker\app\services\chain\ChainInitUser;
use plugin\tracker\app\services\chain\ChainIp;
use plugin\tracker\app\services\chain\ChainPeerRules;
use plugin\tracker\app\services\chain\ChainPort;
use plugin\tracker\app\services\chain\ChainTorrentJob;
use plugin\tracker\app\services\chain\ChainUserAgent;
use plugin\tracker\app\services\chain\ChainUserJob;
use plugin\tracker\core\AnnounceRequest;
use plugin\tracker\core\Guard as GuildInterface;
use plugin\tracker\core\Rocket;
use support\Response;
use Throwable;
use function Iyuu\Tracker\bencode;

/**
 * 服务类
 */
class AnnounceService
{
    /**
     * 【单例模式】获取Pipeline对象实例
     * @return Pipeline
     */
    protected static function pipeline(): Pipeline
    {
        static $pipeline;
        if (null === $pipeline) {
            $pipeline = new Pipeline();
        }
        return $pipeline;
    }

    /**
     * 调度责任链
     * @param AnnounceRequest $announceRequest
     * @param GuildInterface $guard
     * @return Response
     */
    public static function chain(AnnounceRequest $announceRequest, GuildInterface $guard): Response
    {
        $passable = new Rocket($announceRequest, $guard);
        $pipes = [
            [ChainAnnounceLimit::class, 'handle'],
            [ChainIp::class, 'handle'],
            [ChainPort::class, 'handle'],
            [ChainUserAgent::class, 'handle'],
            [ChainInitUser::class, 'handle'],
            [ChainInitTorrent::class, 'handle'],
            [ChainClient::class, 'handle'],
            [ChainPeerRules::class, 'handle'],
            [ChainUserJob::class, 'handle'],
            [ChainTorrentJob::class, 'handle'],
        ];

        return self::pipeline()->send($passable)
            ->whenException(function (Rocket $passable, Throwable $e) {
                // todo... 异常处理
                throw $e;
            })
            ->through($pipes)
            ->then(fn(Rocket $passable) => static::handle($passable));
    }

    /**
     * @param Rocket $rocket
     * @return Response
     */
    protected static function handle(Rocket $rocket): Response
    {
        return bencode('hello announce');
    }
}
