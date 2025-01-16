<?php

namespace plugin\tracker\app\controller;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use plugin\admin\app\controller\Crud;
use plugin\tracker\app\model\Snatched;
use support\exception\BusinessException;
use support\Model;
use support\Request;
use support\Response;

/**
 * 下载记录
 */
class SnatchedController extends Crud
{
    /**
     * @var Snatched
     */
    protected $model = null;

    /**
     * 构造函数
     * @return void
     */
    public function __construct()
    {
        $this->model = new Snatched;
    }

    /**
     * 浏览
     * @return Response
     */
    public function index(): Response
    {
        return view('snatched/index');
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
        return view('snatched/insert');
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
        return view('snatched/update');
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
