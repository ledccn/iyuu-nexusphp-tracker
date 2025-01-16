<?php

return [
    [
        'title' => '种子管理',
        'icon' => 'layui-icon-diamond',
        'key' => 'torrents',
        'weight' => 0,
        'type' => 0,
        'children' => [
            [
                'title' => '全部种子',
                'icon' => '',
                'key' => plugin\tracker\app\controller\TorrentController::class,
                'href' => '/app/tracker/torrent/index',
                'weight' => 0,
                'type' => 1,
            ],
            [
                'title' => '下载记录',
                'icon' => '',
                'key' => plugin\tracker\app\controller\SnatchedController::class,
                'href' => '/app/tracker/snatched/index',
                'weight' => 0,
                'type' => 1,
            ],
        ]
    ],
    [
        'title' => '客户端管理',
        'icon' => 'layui-icon-upload',
        'key' => 'bittorrent',
        'weight' => 0,
        'type' => 0,
        'children' => [
            [
                'title' => '对等节点',
                'icon' => '',
                'key' => plugin\tracker\app\controller\PeerController::class,
                'href' => '/app/tracker/peer/index',
                'weight' => 0,
                'type' => 1,
            ],
            [
                'title' => '白名单',
                'icon' => '',
                'key' => plugin\tracker\app\controller\AgentAllowedFamilyController::class,
                'href' => '/app/tracker/agent-allowed-family/index',
                'weight' => 0,
                'type' => 1,
            ],
            [
                'title' => '黑名单',
                'icon' => '',
                'key' => plugin\tracker\app\controller\AgentAllowedExceptionController::class,
                'href' => '/app/tracker/agent-allowed-exception/index',
                'weight' => 0,
                'type' => 1,
            ]
        ]
    ],
    [
        'title' => 'Nexus用户管理',
        'icon' => 'layui-icon-group',
        'key' => 'np_users',
        'weight' => 0,
        'type' => 0,
        'children' => [
            [
                'title' => '全部用户',
                'icon' => '',
                'key' => plugin\tracker\app\controller\UserController::class,
                'href' => '/app/tracker/user/index',
                'weight' => 0,
                'type' => 1,
            ],
        ]
    ],
];
