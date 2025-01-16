<?php
/**
 * 中间件配置
 * - 中间件执行顺序：全局中间件->应用中间件->路由中间件。
 * - 有多个全局中间件时，按照中间件实际配置顺序执行(应用中间件、路由中间件同理)。
 * - 请求404不会触发任何中间件，包括全局中间件.
 */

return [
    '@' => [],
    '' => [],
    'admin' => [
        plugin\admin\api\Middleware::class,
    ],
    'user' => [
        Ledc\WebmanUser\UserMiddleware::class
    ],
    'plugin.user' => [],
];
