<?php

namespace Ledc\Webman\Traits;

use support\Response;

/**
 * 统一响应格式，生成响应对象
 */
trait HasResponse
{
    /**
     * 响应
     * @param int $code 响应码
     * @param string $msg 消息
     * @param array $data 数据
     */
    final protected function json(int $code, string $msg = 'ok', array $data = []): Response
    {
        return json(['code' => $code, 'data' => $data, 'msg' => $msg]);
    }

    /**
     * 成功响应
     * @param string $msg 消息
     * @param array $data 数据
     */
    final protected function success(string $msg = 'ok', array $data = []): Response
    {
        return $this->json($this->getSuccessCode(), $msg, $data);
    }

    /**
     * 成功响应
     * @param array $data 数据
     * @return Response
     */
    final protected function dataSuccess(array $data = []): Response
    {
        return $this->json($this->getSuccessCode(), 'ok', $data);
    }

    /**
     * 失败响应
     * @param string $msg 消息
     * @param int $code 响应码
     * @param array $data
     * @return Response
     */
    final protected function fail(string $msg = 'fail', int $code = 1, array $data = []): Response
    {
        return $this->json($this->getSuccessCode() === $code ? $this->getErrorCode() : $code, $msg, $data);
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

    /**
     * 格式化枚举的名值数组
     * @param array $items
     * @return array
     */
    final protected function formatSelectEnums(array $items): array
    {
        $formatted = [];
        foreach ($items as $name => $value) {
            $formatted[] = [
                'name' => $name,
                'value' => $value
            ];
        }
        return $formatted;
    }
}
