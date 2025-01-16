<?php

namespace Ledc\WebmanUser\Controller;

use LogicException;
use support\Model;
use support\Response;
use function Ledc\WebmanUser\user_id;

/**
 * 基础控制器
 */
class Base
{
    /**
     * 数据模型
     * @var Model|null
     */
    protected ?Model $model = null;

    /**
     * 无需登录及鉴权的方法
     * @var array|string
     */
    protected array|string $noNeedLogin = [];

    /**
     * 数据限制
     * - false 不做限制，任何人都可以查看该表的所有数据
     * - true 只能看到自己插入的数据
     * @var bool
     */
    protected bool $dataLimit = true;

    /**
     * 数据限制字段
     * - 例如：user_id、uid、uuid
     */
    protected string $dataLimitField = '';

    /**
     * 安全的数据限制及数据限制字段
     * @return bool
     */
    final protected function safeDataLimit(): bool
    {
        if ($this->dataLimit) {
            if (empty($this->dataLimitField)) {
                throw new LogicException('数据限制字段不能为空');
            }

            if (empty(user_id())) {
                throw new LogicException('用户ID为空', 401);
            }
        }

        return $this->dataLimit;
    }

    /**
     * 返回格式化json数据
     * @param int $code
     * @param string $msg
     * @param array $data
     * @return Response
     */
    final protected function json(int $code, string $msg = 'ok', array $data = []): Response
    {
        return json(['code' => $code, 'data' => $data, 'msg' => $msg]);
    }

    /**
     * 成功响应
     * @param array $data
     * @return Response
     */
    final protected function dataSuccess(array $data = []): Response
    {
        return $this->json($this->getSuccessCode(), 'ok', $data);
    }

    /**
     * 成功响应
     * @param string $msg
     * @param array $data
     * @return Response
     */
    final protected function success(string $msg = 'ok', array $data = []): Response
    {
        return $this->json($this->getSuccessCode(), $msg, $data);
    }

    /**
     * 失败响应
     * @param string $msg
     * @param array $data
     * @return Response
     */
    final protected function fail(string $msg = 'fail', array $data = []): Response
    {
        return $this->json($this->getErrorCode(), $msg, $data);
    }

    /**
     * 获取成功响应码
     * @return int
     */
    protected function getSuccessCode(): int
    {
        return 0;
    }

    /**
     * 获取失败响应码
     * @return int
     */
    protected function getErrorCode(): int
    {
        return 1;
    }
}
