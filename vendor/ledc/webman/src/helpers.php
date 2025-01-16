<?php

namespace Ledc\Webman;

use Ledc\Snowflake\RedisSequenceResolver;
use Ledc\Snowflake\Snowflake;
use RedisException;
use support\Redis;
use think\exception\ValidateException;
use think\Validate;

/**
 * 判断是否通过微信客户端访问
 * @return bool
 */
function is_wechat(): bool
{
    return str_contains(request()->header('user-agent', ''), 'MicroMessenger');
}

/**
 * 判断是否通过移动端访问
 * @return bool
 */
function is_mobile(): bool
{
    $userAgent = request()?->header('user-agent', '');
    if (preg_match('/(iphone|ipod|ipad|android|blackberry|webos|windows phone|mobile)/i', $userAgent ?: '')) {
        return true;
    }
    return false;
}

/**
 * 生成20位纯数字订单号
 * - 规则：年月日时分秒 + 6位微秒数（示例值20241101235959123456）
 * @return string
 */
function generate_order_number(): string
{
    [$mSec, $second] = explode(' ', microtime());
    return date('YmdHis', (int)$second) . substr($mSec, 2, 6);
}

/**
 * 生成19位纯数字订单号
 * - 规则：年月日 + 5位当日秒数 + 6位微秒数（示例值2025010166074675841）
 * @return string
 */
function generate_order_sn(): string
{
    [$mSec, $timestamp] = explode(' ', microtime());
    $s = $timestamp - mktime(0, 0, 0);
    return date('Ymd', (int)$timestamp) . str_pad($s, 5, '0', STR_PAD_LEFT) . substr($mSec, 2, 6);
}

/**
 * 雪花ID生成器
 * @return Snowflake
 * @throws RedisException
 */
function snowflake(): Snowflake
{
    $sequence = new RedisSequenceResolver(Redis::connection()->client());
    $sequence->setCachePrefix('Snowflake:' . md5(__FILE__) . ':');
    $snowflake = new Snowflake;
    $snowflake->setSequenceResolver($sequence);

    return $snowflake;
}

/**
 * thinkPHP验证器助手函数
 * @param array $data 待验证的数据
 * @param array|string $validate 验证器类名或者验证规则数组
 * @param array $message 错误提示信息
 * @param bool $batch 是否批量验证
 * @return bool|true
 * @throws ValidateException
 */
function validate(array $data, array|string $validate, array $message = [], bool $batch = false): bool
{
    if (is_array($validate)) {
        $v = new Validate();
        $v->rule($validate);
    } else {
        if (strpos($validate, '.')) {
            // 支持场景
            [$validate, $scene] = explode('.', $validate);
        }
        if (!class_exists($validate)) {
            throw new ValidateException('验证类不存在:' . $validate);
        }
        /** @var Validate $v */
        $v = new $validate();
        if (!empty($scene)) {
            $v->scene($scene);
        }
    }

    return $v->message($message)->batch($batch)->failException(true)->check($data);
}

/**
 * 获取当前版本commit.
 */
function current_git_commit(string $branch = 'master', bool $short = true): string
{
    $filename = sprintf(base_path() . '/.git/refs/heads/%s', $branch);
    clearstatcache();
    if (is_file($filename)) {
        $hash = file_get_contents($filename);
        $hash = trim($hash);
        return $short ? substr($hash, 0, 7) : $hash;
    }

    return '';
}

/**
 * 获取当前版本时间.
 */
function current_git_filemtime(string $branch = 'master', string $format = 'Y-m-d H:i:s'): string
{
    $filename = sprintf(base_path() . '/.git/refs/heads/%s', $branch);
    clearstatcache();
    if (is_file($filename)) {
        $time = filemtime($filename);
        return date($format, $time);
    }

    return '';
}
