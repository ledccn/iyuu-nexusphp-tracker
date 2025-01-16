<?php

namespace plugin\tracker\app\model;

/**
 * 模型观察者：users
 * @usage User::observe(UserObserver::class);
 */
class UserObserver
{
    /**
     * 监听数据即将创建的事件。
     *
     * @param User $model
     * @return void
     */
    public function creating(User $model): void
    {
    }

    /**
     * 监听数据创建后的事件。
     *
     * @param User $model
     * @return void
     */
    public function created(User $model): void
    {
    }

    /**
     * 监听数据即将更新的事件。
     *
     * @param User $model
     * @return void
     */
    public function updating(User $model): void
    {
    }

    /**
     * 监听数据更新后的事件。
     *
     * @param User $model
     * @return void
     */
    public function updated(User $model): void
    {
    }

    /**
     * 监听数据即将保存的事件。
     *
     * @param User $model
     * @return void
     */
    public function saving(User $model): void
    {
    }

    /**
     * 监听数据保存后的事件。
     *
     * @param User $model
     * @return void
     */
    public function saved(User $model): void
    {
    }

    /**
     * 监听数据即将删除的事件。
     *
     * @param User $model
     * @return void
     */
    public function deleting(User $model): void
    {
    }

    /**
     * 监听数据删除后的事件。
     *
     * @param User $model
     * @return void
     */
    public function deleted(User $model): void
    {
    }

    /**
     * 监听数据即将从软删除状态恢复的事件。
     *
     * @param User $model
     * @return void
     */
    public function restoring(User $model): void
    {
    }

    /**
     * 监听数据从软删除状态恢复后的事件。
     *
     * @param User $model
     * @return void
     */
    public function restored(User $model): void
    {
    }
}
