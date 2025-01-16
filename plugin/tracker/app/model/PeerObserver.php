<?php

namespace plugin\tracker\app\model;

/**
 * 模型观察者：peers
 * @usage Peer::observe(PeerObserver::class);
 */
class PeerObserver
{
    /**
     * 监听数据即将创建的事件。
     *
     * @param Peer $model
     * @return void
     */
    public function creating(Peer $model): void
    {
    }

    /**
     * 监听数据创建后的事件。
     *
     * @param Peer $model
     * @return void
     */
    public function created(Peer $model): void
    {
    }

    /**
     * 监听数据即将更新的事件。
     *
     * @param Peer $model
     * @return void
     */
    public function updating(Peer $model): void
    {
    }

    /**
     * 监听数据更新后的事件。
     *
     * @param Peer $model
     * @return void
     */
    public function updated(Peer $model): void
    {
    }

    /**
     * 监听数据即将保存的事件。
     *
     * @param Peer $model
     * @return void
     */
    public function saving(Peer $model): void
    {
    }

    /**
     * 监听数据保存后的事件。
     *
     * @param Peer $model
     * @return void
     */
    public function saved(Peer $model): void
    {
    }

    /**
     * 监听数据即将删除的事件。
     *
     * @param Peer $model
     * @return void
     */
    public function deleting(Peer $model): void
    {
    }

    /**
     * 监听数据删除后的事件。
     *
     * @param Peer $model
     * @return void
     */
    public function deleted(Peer $model): void
    {
    }

    /**
     * 监听数据即将从软删除状态恢复的事件。
     *
     * @param Peer $model
     * @return void
     */
    public function restoring(Peer $model): void
    {
    }

    /**
     * 监听数据从软删除状态恢复后的事件。
     *
     * @param Peer $model
     * @return void
     */
    public function restored(Peer $model): void
    {
    }
}
