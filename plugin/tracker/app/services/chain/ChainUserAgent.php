<?php

namespace plugin\tracker\app\services\chain;

use Closure;
use InvalidArgumentException;
use plugin\tracker\core\Chain;
use plugin\tracker\core\Rocket;
use support\exception\BusinessException;
use support\Response;

/**
 * 检查User-Agent
 */
class ChainUserAgent implements Chain
{
    /**
     * @param Rocket $rocket
     * @param Closure $next
     * @return Response
     */
    public static function handle(Rocket $rocket, Closure $next): Response
    {
        $ua = request()->header('User-Agent');
        if (empty($ua)) {
            throw new InvalidArgumentException('User-Agent empty');
        }

        // 最大长度
        if (strlen($ua) > 70) {
            throw new InvalidArgumentException('User-Agent too long!');
        }

        // 检查浏览器特征
        if (preg_match('/(Mozilla|Browser|Chrome|Safari|AppleWebKit|Opera|Links|Lynx|Bot|Unknown)/i', $ua)) {
            throw new BusinessException('Browser access blocked!');
        }

        $headers = request()->header();
        // 检查其他特征
        if (isset($headers["accept-language"]) || isset($headers["accept-charset"]) || isset($headers["referer"]) || isset($headers["want-digest"])) {
            throw new BusinessException('Anti-Cheater: You cannot use this agent');
        }

        return $next($rocket);
    }
}
