<?php

namespace plugin\tracker\app\model;

/**
 * 模型观察者：agent_allowed_exception
 * @usage AgentAllowedException::observe(AgentAllowedExceptionObserver::class);
 */
class AgentAllowedExceptionObserver
{
    /**
     * 监听数据即将创建的事件。
     *
     * @param AgentAllowedException $model
     * @return void
     */
    public function creating(AgentAllowedException $model): void
    {
    }

    /**
     * 监听数据创建后的事件。
     *
     * @param AgentAllowedException $model
     * @return void
     */
    public function created(AgentAllowedException $model): void
    {
    }

    /**
     * 监听数据即将更新的事件。
     *
     * @param AgentAllowedException $model
     * @return void
     */
    public function updating(AgentAllowedException $model): void
    {
    }

    /**
     * 监听数据更新后的事件。
     *
     * @param AgentAllowedException $model
     * @return void
     */
    public function updated(AgentAllowedException $model): void
    {
    }

    /**
     * 监听数据即将保存的事件。
     *
     * @param AgentAllowedException $model
     * @return void
     */
    public function saving(AgentAllowedException $model): void
    {
    }

    /**
     * 监听数据保存后的事件。
     *
     * @param AgentAllowedException $model
     * @return void
     */
    public function saved(AgentAllowedException $model): void
    {
    }

    /**
     * 监听数据即将删除的事件。
     *
     * @param AgentAllowedException $model
     * @return void
     */
    public function deleting(AgentAllowedException $model): void
    {
    }

    /**
     * 监听数据删除后的事件。
     *
     * @param AgentAllowedException $model
     * @return void
     */
    public function deleted(AgentAllowedException $model): void
    {
    }

    /**
     * 监听数据即将从软删除状态恢复的事件。
     *
     * @param AgentAllowedException $model
     * @return void
     */
    public function restoring(AgentAllowedException $model): void
    {
    }

    /**
     * 监听数据从软删除状态恢复后的事件。
     *
     * @param AgentAllowedException $model
     * @return void
     */
    public function restored(AgentAllowedException $model): void
    {
    }
}
