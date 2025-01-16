<?php

namespace plugin\tracker\app\services\chain;

use Closure;
use InvalidArgumentException;
use Iyuu\Tracker\Cache\TorrentNotExistsCache;
use Iyuu\Tracker\Cache\TorrentsCache;
use plugin\tracker\app\model\Torrent;
use plugin\tracker\core\Chain;
use plugin\tracker\core\Rocket;
use support\Response;

/**
 * 初始化种子
 */
class ChainInitTorrent implements Chain
{
    /**
     * @param Rocket $rocket
     * @param Closure $next
     * @return Response
     */
    public static function handle(Rocket $rocket, Closure $next): Response
    {
        $req = $rocket->getAnnounceRequest();
        $info_hash = $req->info_hash;

        $notExistsCache = TorrentNotExistsCache::instance();
        if (false !== $notExistsCache->zScore($info_hash)) {
            $notExistsCache->zAdd(time(), $info_hash);
            throw new InvalidArgumentException('Torrent not exists');
        }

        $torrentCache = TorrentsCache::instance();
        $_torrent = $torrentCache->get($info_hash);
        if (empty($_torrent)) {
            $torrent = Torrent::queryByInfoHash($info_hash);
            if (!($torrent instanceof Torrent)) {
                $notExistsCache->zAdd(time(), $info_hash);
                throw new InvalidArgumentException('torrent not registered with this tracker');
            }
            Torrent::refreshCache($info_hash, $torrent);
        } else {
            // info_hash 二进制处理 hex2bin
            $torrent = new Torrent();
            foreach ($_torrent as $key => $value) {
                $torrent->{$key} = $value;
            }
            $torrent->exists = true;
        }

        // 验证种子状态
        // todo... 是否判断 see banned权限
        if ($torrent->banned === 'yes') {
            throw new InvalidArgumentException('Torrent banned');
        }

        // todo... 是否判断 see banned权限
        if ((int)$torrent->approval_status !== Torrent::APPROVAL_STATUS_ALLOW) {
            throw new InvalidArgumentException('Torrent review not approved');
        }

        // 注入种子对象
        $rocket->setTorrent($torrent);

        return $next($rocket);
    }
}
