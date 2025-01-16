<?php

namespace plugin\tracker\app\controller;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use plugin\admin\app\controller\Crud;
use plugin\tracker\app\model\Torrent;
use support\exception\BusinessException;
use support\Model;
use support\Request;
use support\Response;

/**
 * 全部种子
 */
class TorrentController extends Crud
{
    /**
     * @var Torrent
     */
    protected $model = null;

    /**
     * 构造函数
     * @return void
     */
    public function __construct()
    {
        $this->model = new Torrent;
    }

    /**
     * 浏览
     * @return Response
     */
    public function index(): Response
    {
        return view('torrent/index');
    }

    /**
     * 插入
     * @param Request $request
     * @return Response
     * @throws BusinessException
     */
    public function insert(Request $request): Response
    {
        if ($request->method() === 'POST') {
            return parent::insert($request);
        }
        return view('torrent/insert');
    }

    /**
     * 更新
     * @param Request $request
     * @return Response
     * @throws BusinessException
     */
    public function update(Request $request): Response
    {
        if ($request->method() === 'POST') {
            return parent::update($request);
        }
        return view('torrent/update');
    }

    /**
     * 查询数据库后置方法，可用于修改数据
     * @param mixed $items 原数据
     * @return mixed 修改后数据
     */
    protected function afterQuery($items): mixed
    {
        /** @var Torrent $item */
        foreach ($items as $item) {
            $item->info_hash = bin2hex($item->info_hash);
        }
        return $items;
    }

    /**
     * @param array $where
     * @param string|null $field
     * @param string $order
     * @return EloquentBuilder|QueryBuilder|Model
     */
    protected function doSelect(array $where, string $field = null, string $order = 'desc'): EloquentBuilder|Model|QueryBuilder
    {
        if (empty($field)) {
            $field = $this->model->getKeyName();
            $order = 'desc';
        }
        return parent::doSelect($where, $field, $order);
    }
}
