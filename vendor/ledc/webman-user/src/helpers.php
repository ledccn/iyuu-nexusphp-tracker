<?php

namespace Ledc\WebmanUser;

use plugin\admin\app\model\Base;
use plugin\admin\app\model\User;
use support\exception\BusinessException;
use support\Model;
use support\Redis;

/**
 * 当前登录用户id
 * @return integer|null
 */
function user_id(): ?int
{
    return session('user.id');
}

/**
 * 当前登录用户
 * @param array|string|null $fields
 * @return array|mixed|null
 */
function user(array|string $fields = null): mixed
{
    refresh_user_session();
    if (!$user = session('user')) {
        return null;
    }
    if ($fields === null) {
        return $user;
    }
    if (is_array($fields)) {
        $results = [];
        foreach ($fields as $field) {
            $results[$field] = $user[$field] ?? null;
        }
        return $results;
    }
    return $user[$fields] ?? null;
}

/**
 * 刷新当前用户session
 * @param bool $force
 * @return void
 */
function refresh_user_session(bool $force = false)
{
    if (!$user_id = user_id()) {
        return null;
    }
    $time_now = time();
    // session在2秒内不刷新
    $session_ttl = 2;
    $session_last_update_time = session('user.session_last_update_time', 0);
    if (!$force && $time_now - $session_last_update_time < $session_ttl) {
        return null;
    }
    $session = request()->session();
    $user = User::find($user_id);
    if (!$user) {
        $session->forget('user');
        return null;
    }
    $user = $user->toArray();
    unset($user['password']);
    $user['session_last_update_time'] = $time_now;
    $session->set('user', $user);
}

/**
 * 检查模型的只读字段，禁止变更
 * @param array $readonly
 * @param Model|Base $model
 * @return void
 * @throws BusinessException
 */
function model_verify_readonly_field(array $readonly, Model|Base $model): void
{
    $dirty = $model->getDirty();
    foreach ($readonly as $field) {
        if (array_key_exists($field, $dirty)) {
            throw new BusinessException("禁止修改只读字段{$field}");
        }
    }
}

/**
 * Redis的setNx指令，支持同时设置ttl
 * - 使用lua脚本实现
 * @param string $key 缓存的key
 * @param string $value 缓存的value
 * @param int $ttl 存活的ttl，单位秒
 * @return bool
 */
function redis_set_nx(string $key, string $value, int $ttl = 10): bool
{
    static $scriptSha = null;
    if (!$scriptSha) {
        $script = <<<luascript
local result = redis.call('SETNX', KEYS[1], ARGV[1]);
if result == 1 then
    return redis.call('expire', KEYS[1], ARGV[2])
else
    return 0
end
luascript;
        $scriptSha = Redis::script('load', $script);
    }
    return (bool)Redis::rawCommand('evalsha', $scriptSha, 1, $key, $value, $ttl);
}

/**
 * Redis的incr指令，支持设置ttl
 * - 使用lua脚本实现
 * @param string $key 缓存的key
 * @param int $ttl 存活的ttl，单位秒
 * @return int
 */
function redis_incr(string $key, int $ttl = 10): int
{
    static $scriptSha = null;
    if (!$scriptSha) {
        $script = <<<luascript
if redis.call('set', KEYS[1], ARGV[1], "EX", ARGV[2], "NX") then
    return ARGV[1]
else
    return redis.call('incr', KEYS[1])
end
luascript;
        $scriptSha = Redis::script('load', $script);
    }
    return (int)Redis::rawCommand('evalsha', $scriptSha, 1, $key, 1, $ttl);
}

/**
 * 用Redis限流
 * - 使用lua脚本实现
 * @param string $key 限制资源：KEY
 * @param int $limit 限制规则：次数
 * @param int $window_time 窗口时间，单位：秒
 * @return int
 */
function redis_rate_limiter(string $key, int $limit, int $window_time = 10): int
{
    static $scriptSha = null;
    if (!$scriptSha) {
        $script = <<<luascript
if redis.call('set', KEYS[1], 1, "EX", ARGV[2], "NX") then
    return 1
else
    if tonumber(redis.call("GET", KEYS[1])) >= tonumber(ARGV[1]) then
        return 0
    else
        return redis.call("INCR", KEYS[1])
    end
end
luascript;
        $scriptSha = Redis::script('load', $script);
    }
    return (int)Redis::rawCommand('evalsha', $scriptSha, 1, $key, $limit, $window_time);
}