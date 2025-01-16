<?php

namespace plugin\tracker\app\controller;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use plugin\admin\app\controller\Crud;
use plugin\tracker\app\model\User;
use support\exception\BusinessException;
use support\Model;
use support\Request;
use support\Response;

/**
 * 用户管理
 */
class UserController extends Crud
{
    /**
     * @var User
     */
    protected $model = null;

    /**
     * 构造函数
     * @return void
     */
    public function __construct()
    {
        $this->model = new User;
    }

    /**
     * 浏览
     * @return Response
     */
    public function index(): Response
    {
        return view('user/index');
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
        return view('user/insert');
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
        return view('user/update');
    }

    /**
     * 查询
     * @param Request $request
     * @return Response
     * @throws BusinessException
     */
    public function select(Request $request): Response
    {
        [$where, $format, $limit, $field, $order] = $this->selectInput($request);
        unset($where['page']);
        $query = $this->doSelect($where, $field, $order);
        return $this->doFormat($query, $format, $limit);
    }

    /**
     * 查询数据库后置方法，可用于修改数据
     * @param mixed $items 原数据
     * @return mixed 修改后数据
     */
    protected function afterQuery($items): mixed
    {
        /** @var User $item */
        foreach ($items as $item) {
            $item->secret = bin2hex($item->secret);
            $item->editsecret = bin2hex($item->editsecret);
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
