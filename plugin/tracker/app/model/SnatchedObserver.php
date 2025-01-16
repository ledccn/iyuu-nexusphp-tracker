<?php

namespace plugin\tracker\app\model;

/**
 * 模型观察者：snatched
 * @usage Snatched::observe(SnatchedObserver::class);
 */
class SnatchedObserver
{
    /**
     * 监听数据即将创建的事件。
     *
     * @param Snatched $model
     * @return void
     */
    public function creating(Snatched $model): void
    {
    }

    /**
     * 监听数据创建后的事件。
     *
     * @param Snatched $model
     * @return void
     */
    public function created(Snatched $model): void
    {
    }

    /**
     * 监听数据即将更新的事件。
     *
     * @param Snatched $model
     * @return void
     */
    public function updating(Snatched $model): void
    {
    }

    /**
     * 监听数据更新后的事件。
     *
     * @param Snatched $model
     * @return void
     */
    public function updated(Snatched $model): void
    {
    }

    /**
     * 监听数据即将保存的事件。
     *
     * @param Snatched $model
     * @return void
     */
    public function saving(Snatched $model): void
    {
    }

    /**
     * 监听数据保存后的事件。
     *
     * @param Snatched $model
     * @return void
     */
    public function saved(Snatched $model): void
    {
    }

    /**
     * 监听数据即将删除的事件。
     *
     * @param Snatched $model
     * @return void
     */
    public function deleting(Snatched $model): void
    {
    }

    /**
     * 监听数据删除后的事件。
     *
     * @param Snatched $model
     * @return void
     */
    public function deleted(Snatched $model): void
    {
    }

    /**
     * 监听数据即将从软删除状态恢复的事件。
     *
     * @param Snatched $model
     * @return void
     */
    public function restoring(Snatched $model): void
    {
    }

    /**
     * 监听数据从软删除状态恢复后的事件。
     *
     * @param Snatched $model
     * @return void
     */
    public function restored(Snatched $model): void
    {
    }
}
