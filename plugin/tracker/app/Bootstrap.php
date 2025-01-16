<?php

namespace plugin\tracker\app;

use plugin\tracker\app\model\AgentAllowedException;
use plugin\tracker\app\model\AgentAllowedExceptionObserver;
use plugin\tracker\app\model\AgentAllowedFamily;
use plugin\tracker\app\model\AgentAllowedFamilyObserver;
use plugin\tracker\app\model\Category;
use plugin\tracker\app\model\CategoryObserver;
use plugin\tracker\app\model\Peer;
use plugin\tracker\app\model\PeerObserver;
use plugin\tracker\app\model\Snatched;
use plugin\tracker\app\model\SnatchedObserver;
use plugin\tracker\app\model\Torrent;
use plugin\tracker\app\model\TorrentObserver;
use plugin\tracker\app\model\User;
use plugin\tracker\app\model\UserObserver;
use Workerman\Worker;

/**
 * 进程启动时onWorkerStart时运行的回调配置
 * @link https://learnku.com/articles/6657/model-events-and-observer-in-laravel
 */
class Bootstrap implements \Webman\Bootstrap
{
    /**
     * @var Worker|null
     */
    private static ?Worker $worker = null;

    /**
     * @param Worker|null $worker
     * @return void
     */
    public static function start(?Worker $worker): void
    {
        static::$worker = $worker;
        //【新增】依次触发的顺序是：
        //saving -> creating -> created -> saved

        //【更新】依次触发的顺序是:
        //saving -> updating -> updated -> saved

        // updating 和 updated 会在数据库中的真值修改前后触发。
        // saving 和 saved 则会在 Eloquent 实例的 original 数组真值更改前后触发
        AgentAllowedException::observe(AgentAllowedExceptionObserver::class);
        AgentAllowedFamily::observe(AgentAllowedFamilyObserver::class);
        Category::observe(CategoryObserver::class);
        Peer::observe(PeerObserver::class);
        Snatched::observe(SnatchedObserver::class);
        Torrent::observe(TorrentObserver::class);
        User::observe(UserObserver::class);
    }

    /**
     * Get worker.
     * @return Worker|null
     */
    public static function worker(): ?Worker
    {
        return static::$worker;
    }
}
