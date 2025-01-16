<?php

namespace app\middleware;

use Webman\MiddlewareInterface;
use Webman\Http\Response;
use Webman\Http\Request;

/**
 * Class StaticFile
 * @package app\middleware
 */
class StaticFile implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {
        // Access to files beginning with. Is prohibited
        if (str_contains($request->path(), '/.')) {
            return response('<h1>403 forbidden</h1>', 403);
        }
        /** @var Response $response */
        $response = $handler($request);

        return $response;
    }
}
