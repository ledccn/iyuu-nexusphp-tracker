<?php

namespace app\model;

use plugin\admin\app\model\User as WaUserByPluginAdmin;
use plugin\user\app\model\User as WaUserByPluginUser;

/**
 * 模型观察者：wa_users
 */
class WaUserObserver
{
    /**
     * 监听数据即将创建的事件。
     * @param WaUserByPluginAdmin|WaUserByPluginUser $model
     * @return void
     */
    public function creating(WaUserByPluginAdmin|WaUserByPluginUser $model): void
    {
        if (empty($model->avatar)) {
            $model->avatar = '/app/user/default-avatar.png';
        }
        $model->token = md5(microtime(true) . uniqid(time()) . mt_rand());
    }

    /**
     * 监听数据创建后的事件。
     * @param WaUserByPluginAdmin|WaUserByPluginUser $model
     * @return void
     */
    public function created(WaUserByPluginAdmin|WaUserByPluginUser $model): void
    {
    }

    /**
     * 监听数据即将更新的事件。
     * @param WaUserByPluginAdmin|WaUserByPluginUser $model
     * @return void
     */
    public function updating(WaUserByPluginAdmin|WaUserByPluginUser $model): void
    {
    }

    /**
     * 监听数据更新后的事件。
     * @param WaUserByPluginAdmin|WaUserByPluginUser $model
     * @return void
     */
    public function updated(WaUserByPluginAdmin|WaUserByPluginUser $model): void
    {
    }

    /**
     * 监听数据即将保存的事件。
     * @param WaUserByPluginAdmin|WaUserByPluginUser $model
     * @return void
     */
    public function saving(WaUserByPluginAdmin|WaUserByPluginUser $model): void
    {
    }

    /**
     * 监听数据保存后的事件。
     * @param WaUserByPluginAdmin|WaUserByPluginUser $model
     * @return void
     */
    public function saved(WaUserByPluginAdmin|WaUserByPluginUser $model): void
    {
    }

    /**
     * 监听数据即将删除的事件。
     * @param WaUserByPluginAdmin|WaUserByPluginUser $model
     * @return void
     */
    public function deleting(WaUserByPluginAdmin|WaUserByPluginUser $model): void
    {
    }

    /**
     * 监听数据删除后的事件。
     * @param WaUserByPluginAdmin|WaUserByPluginUser $model
     * @return void
     */
    public function deleted(WaUserByPluginAdmin|WaUserByPluginUser $model): void
    {
    }

    /**
     * 监听数据即将从软删除状态恢复的事件。
     * @param WaUserByPluginAdmin|WaUserByPluginUser $model
     * @return void
     */
    public function restoring(WaUserByPluginAdmin|WaUserByPluginUser $model): void
    {
    }

    /**
     * 监听数据从软删除状态恢复后的事件。
     * @param WaUserByPluginAdmin|WaUserByPluginUser $model
     * @return void
     */
    public function restored(WaUserByPluginAdmin|WaUserByPluginUser $model): void
    {
    }
}
