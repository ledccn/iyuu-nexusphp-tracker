<?php

namespace Ledc\Webman\Traits;

use Exception;
use plugin\admin\app\model\Upload;
use Random\RandomException;
use support\exception\BusinessException;
use support\Request;
use support\Response;
use Throwable;

/**
 * 支持文件上传
 */
trait HasUpload
{
    /**
     * 判断是否在附件管理内插入记录
     * @return bool
     */
    protected function isUploadInsertLog(): bool
    {
        return true;
    }

    /**
     * 获取上传文件存放的根目录（真实文件夹）
     * - 右侧有斜杠
     * @return string
     */
    protected function getBaseDir(): string
    {
        return base_path() . '/plugin/admin/public/';
    }

    /**
     * 获取上传文件存放的相对文件夹
     * - 左侧有斜杠
     * @return string
     */
    protected function getRelativeDir(): string
    {
        return '/upload/files/' . date('Ymd');
    }

    /**
     * 获取上传文件后资源的url前缀
     * - 两侧有斜杠
     * @return string
     */
    protected function getUploadedUrlPrefix(): string
    {
        return '/app/admin/';
    }

    /**
     * 获取允许上传的文件扩展名
     * @return array
     */
    protected function getAcceptUploadExtension(): array
    {
        return ['jpg', 'jpeg', 'gif', 'png'];
    }

    /**
     * 上传文件
     * @param Request $request
     * @return Response
     * @throws Exception|Throwable
     */
    public function upload(Request $request): Response
    {
        if ($request->method() !== 'POST') {
            throw new BusinessException('上传文件时仅支持POST请求', 200);
        }

        $file = current($request->file());
        if (!$file || !$file->isValid()) {
            return json(['code' => 1, 'data' => [], 'msg' => '未找到文件']);
        }

        $data = $this->base($request);
        if ($this->isUploadInsertLog()) {
            $upload = new Upload;
            $upload->admin_id = admin_id();
            $upload->name = $data['name'];
            [
                $upload->url,
                $upload->name,
                $_,
                $upload->file_size,
                $upload->mime_type,
                $upload->image_width,
                $upload->image_height,
                $upload->ext
            ] = array_values($data);
            $upload->category = $request->post('category');
            $upload->save();
        }

        return json([
            'code' => 0,
            'data' => [
                'url' => $data['url'],
                'name' => $data['name'],
                'size' => $data['size'],
            ],
            'msg' => '上传成功'
        ]);
    }

    /**
     * 获取上传数据
     * @param Request $request
     * @return array
     * @throws BusinessException|RandomException
     */
    protected function base(Request $request): array
    {
        $base_dir = rtrim($this->getBaseDir(), '/') . '/';
        $relative_dir = trim($this->getRelativeDir(), '/');
        $_url_prefix = trim($this->getUploadedUrlPrefix(), '/');
        $url_prefix = $_url_prefix ? "/{$_url_prefix}/" : '/';
        $file = current($request->file());
        if (!$file || !$file->isValid()) {
            throw new BusinessException('未找到上传文件', 400);
        }

        $full_dir = $base_dir . $relative_dir;
        if (!is_dir($full_dir)) {
            mkdir($full_dir, 0777, true);
        }

        $ext = $file->getUploadExtension() ?: null;
        $mime_type = $file->getUploadMimeType();
        $file_name = $file->getUploadName();
        $file_size = $file->getSize();

        if (!$ext && $file_name === 'blob') {
            [$___image, $ext] = explode('/', $mime_type);
            unset($___image);
        }

        $ext = strtolower($ext);
        $ext_forbidden_map = ['php', 'php3', 'php5', 'css', 'js', 'html', 'htm', 'asp', 'jsp'];
        if (in_array($ext, $ext_forbidden_map)) {
            throw new BusinessException('不支持该格式的文件上传', 400);
        }

        $acceptUploadExtension = $this->getAcceptUploadExtension();
        if (!empty($acceptUploadExtension) && false === in_array($ext, $acceptUploadExtension, true)) {
            throw new BusinessException('不支持该扩展名的文件上传', 400);
        }

        $relative_path = $relative_dir . '/' . bin2hex(pack('Nn', time(), random_int(1, 65535))) . ".$ext";
        $full_path = $base_dir . $relative_path;
        $file->move($full_path);
        $image_with = $image_height = 0;
        if ($img_info = getimagesize($full_path)) {
            [$image_with, $image_height] = $img_info;
            $mime_type = $img_info['mime'];
        }
        return [
            'url' => $url_prefix . $relative_path,
            'name' => $file_name,
            'realpath' => $full_path,
            'size' => $file_size,
            'mime_type' => $mime_type,
            'image_with' => $image_with,
            'image_height' => $image_height,
            'ext' => $ext,
        ];
    }
}
