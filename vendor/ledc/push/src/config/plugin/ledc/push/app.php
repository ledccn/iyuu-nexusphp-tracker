<?php

use Ledc\Push\Pipelines\UniqidPipeline;
use Ledc\Push\Pipelines\WebmanAdmin;

return [
    'enable'       => true,
    'websocket'    => 'websocket://0.0.0.0:3131',
    'api'          => 'http://0.0.0.0:3232',
    'app_key'      => 'f4303b282784d49a63cc220cb574e4f8',
    'app_secret'   => 'e02643dac228021160cff2dfad0ea8da',
    'channel_hook' => 'http://127.0.0.1:8787/plugin/ledc/push/hook',
    'auth'         => '/plugin/ledc/push/auth',
    'pipeline' => [
        [WebmanAdmin::class, 'process'],
        [UniqidPipeline::class, 'process'],
    ],
];
