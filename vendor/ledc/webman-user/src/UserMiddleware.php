<?php

namespace Ledc\WebmanUser;

use LogicException;
use ReflectionClass;
use ReflectionException;
use Throwable;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

/**
 * 用户中间件
 * - 支持路由参数
 * - 支持OPTIONS请求的空响应
 * - 支持JSON响应
 * - 支持验证数据限制
 */
class UserMiddleware implements MiddlewareInterface
{
    /**
     * 无需登录的方法
     * - 路由传参数或控制器属性.
     */
    public const string noNeedLogin = 'noNeedLogin';

    /**
     * 构造函数
     * @param array $excludedApps 排除的应用
     */
    public function __construct(protected array $excludedApps = [])
    {
    }

    /**
     * @param Request|\support\Request $request
     * @param callable $handler
     * @return Response
     * @throws ReflectionException
     */
    public function process(Request|\support\Request $request, callable $handler): Response
    {
        // 当前请求的应用属于排除列表，则忽略
        if (in_array($request->app, $this->excludedApps)) {
            return $handler($request);
        }

        // OPTIONS请求，直接返回
        if ('OPTIONS' === $request->method()) {
            return response('');
        }

        try {
            $controller = $request->controller;
            $action = $request->action;
            $route = $request->route;

            // 401是未登录时固定返回码
            $code = 401;
            $msg = '请登录';

            /**
             * 无控制器信息说明是函数调用，函数不属于任何控制器，鉴权操作应该在函数内部完成。
             */
            if ($controller) {
                // 获取控制器鉴权信息
                $class = new ReflectionClass($controller);
                $properties = $class->getDefaultProperties();
                $noNeedLogin = $properties[self::noNeedLogin] ?? [];
                $dataLimit = $properties['dataLimit'] ?? true;
                $dataLimitField = $properties['dataLimitField'] ?? '';
                // 判断是否跳过登录验证
                if ('*' === $noNeedLogin || in_array('*', $noNeedLogin, true) || in_array($action, $noNeedLogin, true)) {
                    // 不需要登录
                    return $handler($request);
                }

                // 需要登录，验证数据限制
                if ($dataLimit && empty($dataLimitField)) {
                    throw new LogicException('控制器错误：数据限制字段不能为空');
                }
            } else {
                // 默认路由 $request->route为null，所以需要判断 $request->route 是否为空
                if (!$route) {
                    return $handler($request);
                }

                // 路由参数
                if ($route->param(self::noNeedLogin)) {
                    // 指定路由不用登录
                    return $handler($request);
                }
            }

            // 判断是否已登录
            if (session('user') && user_id()) {
                return $handler($request);
            }
        } catch (ReflectionException $exception) {
            $msg = '控制器不存在';
            $code = 404;
        } catch (Throwable $throwable) {
            $msg = $throwable->getMessage();
            $code = 500;
        }

        // 支持JSON返回格式
        if ($request->expectsJson()) {
            $response = json(['code' => $code, 'msg' => $msg, 'data' => []]);
        } else {
            $response = redirect('/app/user/login');
        }

        return $response;
    }
}
