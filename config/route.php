<?php
/**
 * 路由
 */

use support\Request;
use Webman\Route;

// 回退路由
Route::fallback(function (Request $request) {
    $default = $request->acceptJson() ? json(['code' => 404, 'msg' => '404 not found']) : response('404 not found', 404);
    $response = 'OPTIONS' === strtoupper($request->method()) ? response('', 204) : $default;
    $response->withHeaders([
        'Access-Control-Allow-Credentials' => 'true',
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => '*',
        'Access-Control-Allow-Headers' => '*',
    ]);

    return $response;
});
