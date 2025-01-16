<?php

namespace Iyuu\Tracker;

use Exception;
use Rhilip\Bencode\Bencode;
use support\Response;
use XdbSearcher;

/**
 * @param object|array|int|string $data
 * @param int $status
 * @param array $headers
 * @return Response
 */
function bencode(object|array|int|string $data, int $status = 200, array $headers = []): Response
{
    if (empty($headers)) {
        $headers = [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Connection' => 'close',
            'Pragma' => 'no-cache'
        ];
    }
    return new Response($status, $headers, Bencode::encode($data));
}

/**
 * 失败响应
 * @param string $message
 * @return Response
 */
function fail_bencode_response(string $message): Response
{
    return bencode(['failure reason' => $message]);
}

/**
 * Ip2region
 * - Ip2region (2.0 - xdb) 是一个离线 IP 数据管理框架和定位库，支持亿级别的数据段
 * - 10微秒级别的查询性能，提供了许多主流编程语言的 xdb 数据管理引擎的实现。
 * @like https://gitee.com/lionsoul/ip2region
 * @param string $ip
 * @return array
 * @throws Exception
 */
function ip2region(string $ip): array
{
    $searcher = XdbSearcher::newWithFileOnly(null);
    $region = $searcher->search($ip);
    if (null === $region) {
        // something is wrong
        throw new Exception('failed search');
    }
    //固定格式：国家|区域|省份|城市|ISP，缺省的地域信息默认是0
    [$country, $temp, $province, $city, $isp] = explode('|', $region);
    return compact('country', 'province', 'city', 'isp', 'ip', 'region');
}
