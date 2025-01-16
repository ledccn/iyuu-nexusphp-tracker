<?php

use plugin\tracker\app\controller\IndexController;
use support\Request;
use Webman\Route;

Route::any('/tracker/announce', [IndexController::class, 'announce']);
Route::any('/tracker/scrape', [IndexController::class, 'scrape']);

// 回退路由
Route::fallback(function (Request $request) {
    $default = response('404 not found', 404);
    $response = 'OPTIONS' === strtoupper($request->method()) ? response('', 204) : $default;
    $response->withHeaders([
        'Access-Control-Allow-Credentials' => 'true',
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => '*',
        'Access-Control-Allow-Headers' => '*',
    ]);

    return $response;
}, 'tracker');

//关闭默认路由
//Route::disableDefaultRoute();
