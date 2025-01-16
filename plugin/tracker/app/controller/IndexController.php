<?php

namespace plugin\tracker\app\controller;

use Iyuu\Tracker\TrackerValidate;
use plugin\tracker\app\services\AnnounceService;
use plugin\tracker\app\services\Guard;
use plugin\tracker\core\AnnounceRequest;
use plugin\tracker\core\Repository;
use support\Request;
use support\Response;
use Throwable;
use function Iyuu\Tracker\bencode;

/**
 * 默认控制器
 */
class IndexController
{
    /**
     * 无需登录及鉴权的方法
     * @var array
     */
    protected array $noNeedLogin = ['index', 'announce', 'scrape'];

    /**
     * 默认方法
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        return bencode('hello world');
    }

    /**
     * Announce
     * @param Request $request
     * @return Response
     */
    public function announce(Request $request): Response
    {
        try {
            $data = $request->getMore([
                'info_hash',
                'peer_id',
                ['ip', ''],
                ['port/d', 0],
                ['downloaded/d', 0],
                ['uploaded/d', 0],
                ['left/d', 0],
                ['event', ''],
                ['compact/b', false],
                ['no_peer_id/b', false],
                ['numwant/d', 50],
                ['ipv4', ''],
                ['ipv6', ''],
                ['corrupt', ''],
                ['key', ''],
                ['authkey', ''],
                ['passkey', ''],
            ]);
            // 兼容
            foreach (['numwant', 'num_want', 'num want'] as $name) {
                if ($numwant = $request->get($name)) {
                    $data['numwant'] = (int)$numwant;
                    break;
                }
            }

            $v = new TrackerValidate();
            $v->scene('announce')->failException()->check($data);

            $announceRequest = new AnnounceRequest($data);
            $guard = new Guard();

            return AnnounceService::chain($announceRequest, $guard);
        } catch (Throwable $throwable) {
            return bencode([
                'failure reason' => $throwable->getMessage(),
                'min interval' => Repository::MIN_ANNOUNCE_WAIT + mt_rand(1, 10)
            ]);
        }
    }

    /**
     * Scrape
     * @param Request $request
     * @return Response
     */
    public function scrape(Request $request): Response
    {
        return bencode('hello scrape');
    }
}
