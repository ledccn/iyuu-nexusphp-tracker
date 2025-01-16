<?php

namespace plugin\tracker\app\model;

/**
 * 模型观察者：agent_allowed_family
 * @usage AgentAllowedFamily::observe(AgentAllowedFamilyObserver::class);
 */
class AgentAllowedFamilyObserver
{
    /**
     * 监听数据即将创建的事件。
     *
     * @param AgentAllowedFamily $model
     * @return void
     */
    public function creating(AgentAllowedFamily $model): void
    {
    }

    /**
     * 监听数据创建后的事件。
     *
     * @param AgentAllowedFamily $model
     * @return void
     */
    public function created(AgentAllowedFamily $model): void
    {
    }

    /**
     * 监听数据即将更新的事件。
     *
     * @param AgentAllowedFamily $model
     * @return void
     */
    public function updating(AgentAllowedFamily $model): void
    {
    }

    /**
     * 监听数据更新后的事件。
     *
     * @param AgentAllowedFamily $model
     * @return void
     */
    public function updated(AgentAllowedFamily $model): void
    {
    }

    /**
     * 监听数据即将保存的事件。
     *
     * @param AgentAllowedFamily $model
     * @return void
     */
    public function saving(AgentAllowedFamily $model): void
    {
    }

    /**
     * 监听数据保存后的事件。
     *
     * @param AgentAllowedFamily $model
     * @return void
     */
    public function saved(AgentAllowedFamily $model): void
    {
    }

    /**
     * 监听数据即将删除的事件。
     *
     * @param AgentAllowedFamily $model
     * @return void
     */
    public function deleting(AgentAllowedFamily $model): void
    {
    }

    /**
     * 监听数据删除后的事件。
     *
     * @param AgentAllowedFamily $model
     * @return void
     */
    public function deleted(AgentAllowedFamily $model): void
    {
    }

    /**
     * 监听数据即将从软删除状态恢复的事件。
     *
     * @param AgentAllowedFamily $model
     * @return void
     */
    public function restoring(AgentAllowedFamily $model): void
    {
    }

    /**
     * 监听数据从软删除状态恢复后的事件。
     *
     * @param AgentAllowedFamily $model
     * @return void
     */
    public function restored(AgentAllowedFamily $model): void
    {
    }
}
