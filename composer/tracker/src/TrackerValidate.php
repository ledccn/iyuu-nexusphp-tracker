<?php

namespace Iyuu\Tracker;

use think\Validate;

/**
 * 验证器
 */
class TrackerValidate extends Validate
{
    /**
     * 当前验证规则
     * @var array
     */
    protected $rule = [
        'info_hash' => 'require|length:20',
        'peer_id' => 'require|length:20',
        'ip' => 'require|max:40',
        'port' => 'require|between:0,65535', // 仅允许stopped事件的端口为0
        'downloaded' => 'require|number',
        'uploaded' => 'require|number',
        'left' => 'require|number',
        'event' => 'require',
        'compact' => 'require',
        'no_peer_id' => 'require',
        'numwant' => 'require|between:1,1000',
        'ipv4' => 'require|max:15',
        'ipv6' => 'require|max:40',
        'corrupt' => 'require',
        'key' => 'require',
        'authkey' => 'require',
        'passkey' => 'require|length:32',
    ];

    /**
     * 验证提示信息
     * @var array
     */
    protected $message = [
        'info_hash.require' => 'info_hash必填',
        'peer_id.require' => '下载器唯一ID必填',
        'ip.require' => 'IP必填',
        'port.require' => '端口必填',
        'downloaded.require' => '十进制下载量必填',
        'uploaded.require' => '十进制上传量必填',
        'left.require' => '十进制仍需下载的字节数必填',
        'event.require' => '客户端的种子事件必填',
        'compact.require' => '紧凑型响应(BEP0023)必填',
        'no_peer_id.require' => '是否返回peer_id必填',
        'numwant.require' => '客户端希望返回的Peer数量必填',
        'ipv4.require' => 'IPv4地址必填',
        'ipv6.require' => 'IPv6地址必填',
        'corrupt.require' => '客户端标识corrupt必填',
        'key.require' => '客户端标识key必填',
        'authkey.require' => 'authkey必填',
        'passkey.require' => 'passkey必填',
    ];

    /**
     * 验证场景定义
     * @var array
     */
    protected $scene = [
        'announce' => ['info_hash', 'peer_id', 'port', 'downloaded', 'uploaded', 'left'],
        'scrape' => [],
    ];

    /**
     * 验证失败是否抛出异常
     * @var bool
     */
    protected $failException = true;
}
