<?php

namespace plugin\tracker\app\model;

/**
 * 模型观察者：categories
 * @usage Category::observe(CategoryObserver::class);
 */
class CategoryObserver
{
    /**
     * 监听数据即将创建的事件。
     *
     * @param Category $model
     * @return void
     */
    public function creating(Category $model): void
    {
    }

    /**
     * 监听数据创建后的事件。
     *
     * @param Category $model
     * @return void
     */
    public function created(Category $model): void
    {
    }

    /**
     * 监听数据即将更新的事件。
     *
     * @param Category $model
     * @return void
     */
    public function updating(Category $model): void
    {
    }

    /**
     * 监听数据更新后的事件。
     *
     * @param Category $model
     * @return void
     */
    public function updated(Category $model): void
    {
    }

    /**
     * 监听数据即将保存的事件。
     *
     * @param Category $model
     * @return void
     */
    public function saving(Category $model): void
    {
    }

    /**
     * 监听数据保存后的事件。
     *
     * @param Category $model
     * @return void
     */
    public function saved(Category $model): void
    {
    }

    /**
     * 监听数据即将删除的事件。
     *
     * @param Category $model
     * @return void
     */
    public function deleting(Category $model): void
    {
    }

    /**
     * 监听数据删除后的事件。
     *
     * @param Category $model
     * @return void
     */
    public function deleted(Category $model): void
    {
    }

    /**
     * 监听数据即将从软删除状态恢复的事件。
     *
     * @param Category $model
     * @return void
     */
    public function restoring(Category $model): void
    {
    }

    /**
     * 监听数据从软删除状态恢复后的事件。
     *
     * @param Category $model
     * @return void
     */
    public function restored(Category $model): void
    {
    }
}
