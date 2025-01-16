<?php

namespace Iyuu\Tracker;

/**
 * 工具类
 */
class Utils
{
    /**
     * @param string $ip
     * @param mixed $flags
     * @return mixed
     */
    public static function isValidIP(string $ip = '', mixed $flags = null): mixed
    {
        return filter_var($ip, FILTER_VALIDATE_IP, $flags);
    }

    /**
     * @param string $ip
     * @return mixed
     */
    public static function isValidIPv4(string $ip = ''): mixed
    {
        return self::isValidIP($ip, FILTER_FLAG_IPV4);
    }

    /**
     * @param string $ip
     * @return mixed
     */
    public static function isValidIPv6(string $ip = ''): mixed
    {
        return self::isValidIP($ip, FILTER_FLAG_IPV6);
    }

    /**
     * @param string $ip
     * @param mixed|null $flags
     * @return mixed
     */
    public static function isPublicIp(string $ip = '', mixed $flags = null): mixed
    {
        /**
         * FILTER_FLAG_NO_PRIV_RANGE:
         *   - Fails validation for the following private IPv4 ranges: 10.0.0.0/8, 172.16.0.0/12 and 192.168.0.0/16.
         *   - Fails validation for the IPv6 addresses starting with FD or FC.
         * FILTER_FLAG_NO_RES_RANGE:
         *   - Fails validation for the following reserved IPv4 ranges: 0.0.0.0/8, 169.254.0.0/16, 127.0.0.0/8 and 240.0.0.0/4.
         *   - Fails validation for the following reserved IPv6 ranges: ::1/128, ::/128, ::ffff:0:0/96 and fe80::/10.
         */
        return self::isValidIP($ip, $flags | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }

    /**
     * @param string $ip
     * @return mixed
     */
    public static function isPublicIPv4(string $ip = ''): mixed
    {
        return self::isPublicIp($ip, FILTER_FLAG_IPV4);
    }

    /**
     * @param string $ip
     * @return mixed
     */
    public static function isPublicIPv6(string $ip = ''): mixed
    {
        return self::isPublicIp($ip, FILTER_FLAG_IPV6);
    }

    /**
     * 判断URL是否编码
     * @param $string
     * @return bool
     */
    public static function isUrlEncoded($string): bool
    {
        $test_string = $string;
        while (urldecode($test_string) != $test_string) {
            $test_string = urldecode($test_string);
        }
        return urlencode($test_string) === $string;
    }
}
