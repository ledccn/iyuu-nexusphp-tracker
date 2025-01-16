<?php

namespace plugin\tracker\app\model;

/**
 * 模型观察者：torrents
 * @usage Torrent::observe(TorrentObserver::class);
 */
class TorrentObserver
{
    /**
     * 监听数据即将创建的事件。
     *
     * @param Torrent $model
     * @return void
     */
    public function creating(Torrent $model): void
    {
    }

    /**
     * 监听数据创建后的事件。
     *
     * @param Torrent $model
     * @return void
     */
    public function created(Torrent $model): void
    {
    }

    /**
     * 监听数据即将更新的事件。
     *
     * @param Torrent $model
     * @return void
     */
    public function updating(Torrent $model): void
    {
    }

    /**
     * 监听数据更新后的事件。
     *
     * @param Torrent $model
     * @return void
     */
    public function updated(Torrent $model): void
    {
    }

    /**
     * 监听数据即将保存的事件。
     *
     * @param Torrent $model
     * @return void
     */
    public function saving(Torrent $model): void
    {
    }

    /**
     * 监听数据保存后的事件。
     *
     * @param Torrent $model
     * @return void
     */
    public function saved(Torrent $model): void
    {
    }

    /**
     * 监听数据即将删除的事件。
     *
     * @param Torrent $model
     * @return void
     */
    public function deleting(Torrent $model): void
    {
    }

    /**
     * 监听数据删除后的事件。
     *
     * @param Torrent $model
     * @return void
     */
    public function deleted(Torrent $model): void
    {
    }

    /**
     * 监听数据即将从软删除状态恢复的事件。
     *
     * @param Torrent $model
     * @return void
     */
    public function restoring(Torrent $model): void
    {
    }

    /**
     * 监听数据从软删除状态恢复后的事件。
     *
     * @param Torrent $model
     * @return void
     */
    public function restored(Torrent $model): void
    {
    }
}
