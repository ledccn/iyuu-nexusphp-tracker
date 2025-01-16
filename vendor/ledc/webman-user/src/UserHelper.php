<?php

namespace Ledc\WebmanUser;

use Exception;
use InvalidArgumentException;
use plugin\admin\app\model\User as UserModel;
use plugin\user\app\model\User;
use support\Request;
use support\Response;
use Webman\Event\Event;

/**
 * 用户助手
 */
class UserHelper
{
    /**
     * 对用户id做登录操作
     * @param int|User|UserModel $id 用户ID
     * @return void
     * @throws Exception
     */
    public static function login(int|User|UserModel $id): void
    {
        if (!class_exists(User::class)) {
            throw new Exception("请安装webman用户模块");
        }

        if ($id instanceof User) {
            $user = $id;
        } else {
            $user_id = $id instanceof UserModel ? $id->id : $id;
            $user = User::find($user_id);
            if (!$user) {
                throw new InvalidArgumentException('用户不存在');
            }
        }

        $request = request();
        $request->session()->set('user', [
            'id' => $user->id,
            'username' => $user->username,
            'nickname' => $user->nickname,
            'avatar' => $user->avatar,
            'email' => $user->email,
            'mobile' => $user->mobile,
        ]);
        // 发布登录事件
        Event::emit('user.login', $user);
        $user->last_ip = $request->getRealIp();
        $user->last_time = date('Y-m-d H:i:s');
        $user->save();
    }

    /**
     * 退出
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public static function logout(Request $request): Response
    {
        if (!class_exists(User::class)) {
            throw new Exception("请安装webman用户模块");
        }

        $session = $request->session();
        $userId = session('user.id');
        if ($userId && $user = User::find($userId)) {
            // 发布退出事件
            Event::emit('user.logout', $user);
        }
        $session->delete('user');
        return redirect('/app/user/login');
    }
}
