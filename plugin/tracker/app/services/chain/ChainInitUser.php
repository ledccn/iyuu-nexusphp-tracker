<?php

namespace plugin\tracker\app\services\chain;

use Closure;
use InvalidArgumentException;
use Iyuu\Tracker\Cache\PasskeyCache;
use Iyuu\Tracker\Cache\PasskeyInvalidCache;
use Iyuu\Tracker\Cache\UserCache;
use plugin\tracker\app\model\User;
use plugin\tracker\core\Chain;
use plugin\tracker\core\Rocket;
use support\Response;
use function Iyuu\Tracker\bencode;

/**
 * 初始化用户
 */
class ChainInitUser implements Chain
{
    /**
     * @param Rocket $rocket
     * @param Closure $next
     * @return Response
     */
    public static function handle(Rocket $rocket, Closure $next): Response
    {
        $req = $rocket->getAnnounceRequest();
        $passkey = $req->passkey;
        if (empty($passkey)) {
            throw new InvalidArgumentException('Require passkey or authkey');
        }

        // 拦截失效passkey
        $passkeyInvalid = PasskeyInvalidCache::instance();
        $last_time = $passkeyInvalid->zScore($passkey);
        if (false !== $last_time) {
            $passkeyInvalid->zIncrBy(abs(ceil(time() - $last_time)), $passkey);
            throw new InvalidArgumentException('Passkey invalid');
        }

        // 从缓存中获取用户信息
        $passkeyCache = PasskeyCache::instance();
        $user_id = $passkeyCache->hGet($passkey);
        if (empty($user_id)) {
            $user = static::notExistsCache($passkey, $passkeyInvalid);
        } else {
            $user = static::existsCache($user_id);

            // 如果重置密钥
            if ($user->passkey !== $passkey) {
                $passkeyCache->del($passkey);
                $passkeyInvalid->zAdd(time(), $passkey);
                throw new InvalidArgumentException('Invalid passkey! Re-download the .torrent from $BASEURL');
            }
        }

        // 验证账号状态
        if ($user->enabled !== 'yes') {
            return bencode($rocket->warningResponseDict('Your account is disabled!', 300));
        }

        if ($user->downloadpos !== 'yes') {
            return bencode($rocket->warningResponseDict('Your downloading privileges have been disabled! (Read the rules)', 300));
        }

        if ($user->parked === 'yes') {
            return bencode($rocket->warningResponseDict('Your account is parked! (Read the FAQ)', 300));
        }

        // 注入用户对象
        $rocket->getGuard()->setUser($user);

        return $next($rocket);
    }

    /**
     * @param string $passkey
     * @param PasskeyInvalidCache $passkeyInvalid
     * @return User
     */
    protected static function notExistsCache(string $passkey, PasskeyInvalidCache $passkeyInvalid): User
    {
        $user = User::queryByPasskey($passkey);
        if ($user instanceof User) {
            User::refreshCache($user);
            return $user;
        }

        $passkeyInvalid->zAdd(time(), $passkey);
        throw new InvalidArgumentException('Invalid passkey! Re-download the .torrent from $BASEURL');
    }

    /**
     * @param int $user_id
     * @return User
     */
    protected static function existsCache(int $user_id): User
    {
        $userInfo = UserCache::instance()->get($user_id);
        if (empty($userInfo)) {
            $user = User::refreshCacheById($user_id);
        } else {
            $user = new User();
            foreach ($userInfo as $field => $value) {
                $user->{$field} = $value;
            }
            $user->exists = true;
        }

        return $user;
    }
}
