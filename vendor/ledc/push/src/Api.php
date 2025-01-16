<?php

namespace Ledc\Push;

use CurlHandle;

/**
 * Pusher推送器HTTP库
 * - Modified from https://github.com/pusher/pusher-http-php
 */
class Api
{
    /**
     * @var array
     */
    protected array $_settings = [
        'timeout' => 2,
    ];

    /**
     * 构造函数
     * @param string $api_address
     * @param string $auth_key
     * @param string $secret
     * @throws PushException
     */
    public function __construct(string $api_address, string $auth_key, string $secret)
    {
        $this->checkCompatibility();
        $this->_settings['api_address'] = $api_address;
        $this->_settings['auth_key'] = $auth_key;
        $this->_settings['secret'] = $secret;
        $this->_settings['base_path'] = '/apps/1024';
    }

    /**
     * 发布事件
     * - 在一个或多个通道上触发事件
     * - 通过提供通道名称和有效负载来触发事件
     * - 可提供发件人的socketId以排除客户端
     * @param array|string $channels 将要发布事件的通道数组
     * @param string $event 事件名称
     * @param mixed $data 事件数据
     * @param string|null $socket_id [可选]发件人的socketId
     * @return bool
     * @throws PushException
     */
    public function trigger(array|string $channels, string $event, mixed $data, string $socket_id = null): bool
    {
        if (is_string($channels)) {
            $channels = array($channels);
        }
        $query_params = array();
        $s_url = $this->_settings['base_path'] . '/events';
        $data_encoded = json_encode($data);
        $post_params = array();
        $post_params['name'] = $event;
        $post_params['data'] = $data_encoded;
        $post_params['channels'] = $channels;
        if ($socket_id !== null) {
            $post_params['socket_id'] = $socket_id;
        }
        $post_value = json_encode($post_params);
        $query_params['body_md5'] = md5($post_value);
        $ch = $this->createCurl($this->_settings['api_address'], $s_url, 'POST', $query_params);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_value);
        $response = $this->execCurl($ch);
        if (200 === $response['status']) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取频道信息
     * @param string $channel
     * @param array $params
     * @return false|object|array
     * @throws PushException
     */
    public function getChannelInfo(string $channel, array $params = []): object|bool|array
    {
        $this->validateChannel($channel);
        $response = $this->get('/channels/' . $channel, $params);
        if (200 === $response['status']) {
            $response = json_decode($response['body']);
        } else {
            $response = false;
        }
        return $response;
    }

    /**
     * @param array $params
     * @return array
     * @throws PushException
     */
    public function getChannels(array $params = []): array
    {
        return $this->get('/channels', $params);
    }

    /**
     * 验证散列算法
     * @return void
     * @throws PushException
     */
    private function checkCompatibility(): void
    {
        if (!in_array('sha256', hash_algos())) {
            throw new PushException('SHA256 appears to be unsupported - make sure you have support for it, or upgrade your version of PHP.');
        }
    }

    /**
     * 验证频道名称
     * @param string $channel
     * @return void
     * @throws PushException
     */
    private function validateChannel(string $channel): void
    {
        if (!preg_match('/\A[-a-zA-Z0-9_=@,.;]+\z/', $channel)) {
            throw new PushException('Invalid channel name ' . $channel);
        }
    }

    /**
     * 验证socket_id
     * @param string|null $socket_id
     * @return void
     * @throws PushException
     */
    private function validateSocketId(?string $socket_id): void
    {
        if ($socket_id !== null && !preg_match('/\A\d+\.\d+\z/', $socket_id)) {
            throw new PushException('Invalid socket ID ' . $socket_id);
        }
    }

    /**
     * 创建curl句柄
     * @param string $domain
     * @param string $s_url
     * @param string $request_method
     * @param array $query_params
     * @return CurlHandle
     * @throws PushException
     */
    private function createCurl(string $domain, string $s_url, string $request_method = 'GET', array $query_params = array()): CurlHandle
    {
        static $ch = null;
        $signed_query = self::buildAuthQueryString(
            $this->_settings['auth_key'],
            $this->_settings['secret'],
            $request_method,
            $s_url,
            $query_params);

        $full_url = $domain . $s_url . '?' . $signed_query;

        if (null === $ch) {
            $ch = curl_init();
            if ($ch === false) {
                throw new PushException('Could not initialise cURL!');
            }
        }

        if (function_exists('curl_reset')) {
            curl_reset($ch);
        }

        curl_setopt($ch, CURLOPT_URL, $full_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Expect:',
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->_settings['timeout']);
        if ($request_method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
        } elseif ($request_method === 'GET') {
            curl_setopt($ch, CURLOPT_POST, 0);
        } // Otherwise let the user configure it

        return $ch;
    }

    /**
     * 实际发起请求
     * @param CurlHandle $ch
     * @return array
     */
    private function execCurl(CurlHandle $ch): array
    {
        $response = [];
        $response['body'] = curl_exec($ch);
        $response['status'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return $response;
    }

    /**
     * @param string $auth_key
     * @param string $auth_secret
     * @param string $request_method
     * @param string $request_path
     * @param array $query_params
     * @return mixed|string
     */
    public static function buildAuthQueryString(
        string $auth_key,
        string $auth_secret,
        string $request_method,
        string $request_path,
        array  $query_params = []
    ): mixed
    {
        $params = [];
        $params['auth_key'] = $auth_key;
        $params['auth_timestamp'] = time();

        $params = array_merge($params, $query_params);
        ksort($params);

        $string_to_sign = "$request_method\n" . $request_path . "\n" . self::arrayImplode('=', '&', $params);

        $auth_signature = hash_hmac('sha256', $string_to_sign, $auth_secret, false);

        $params['auth_signature'] = $auth_signature;
        ksort($params);

        return self::arrayImplode('=', '&', $params);
    }

    /**
     * @param $glue
     * @param $separator
     * @param $array
     * @return mixed|string
     */
    public static function arrayImplode($glue, $separator, $array): mixed
    {
        if (!is_array($array)) {
            return $array;
        }
        $string = array();
        foreach ($array as $key => $val) {
            if (is_array($val)) {
                $val = implode(',', $val);
            }
            $string[] = "{$key}{$glue}{$val}";
        }

        return implode($separator, $string);
    }

    /**
     * GET请求
     * @param string $path
     * @param array $params
     * @return array
     * @throws PushException
     */
    public function get(string $path, array $params = array()): array
    {
        $ch = $this->createCurl($this->_settings['api_address'], $this->_settings['base_path'] . $path, 'GET', $params);
        $response = $this->execCurl($ch);
        if ($response['status'] === 200) {
            $response['result'] = json_decode($response['body'], true);
        }
        return $response;
    }

    /**
     * 签名频道名称、socket_id、自定义数据
     * @param string $channel 频道名称
     * @param string $socket_id 长连接ID
     * @param string|null $custom_data [可选]自定义数据
     * @return false|string
     * @throws PushException
     */
    public function socketAuth(string $channel, string $socket_id, string $custom_data = null): bool|string
    {
        $this->validateChannel($channel);
        $this->validateSocketId($socket_id);

        if ($custom_data) {
            $signature = hash_hmac('sha256', $socket_id . ':' . $channel . ':' . $custom_data, $this->_settings['secret'], false);
        } else {
            $signature = hash_hmac('sha256', $socket_id . ':' . $channel, $this->_settings['secret'], false);
        }

        $signature = array('auth' => $this->_settings['auth_key'] . ':' . $signature);
        if ($custom_data) {
            $signature['channel_data'] = $custom_data;
        }

        return json_encode($signature);
    }

    /**
     * 签名频道名称、socket_id、自定义数据
     * @param string $channel
     * @param string $socket_id
     * @param int|string $user_id
     * @param mixed|null $user_info
     * @return false|string
     * @throws PushException
     */
    public function presenceAuth(string $channel, string $socket_id, int|string $user_id, mixed $user_info = null): bool|string
    {
        $user_data = ['user_id' => $user_id];
        if ($user_info) {
            $user_data['user_info'] = $user_info;
        }

        return $this->socketAuth($channel, $socket_id, json_encode($user_data));
    }
}
