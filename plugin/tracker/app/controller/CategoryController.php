<?php

namespace plugin\tracker\app\controller;

use support\Request;
use support\Response;
use plugin\tracker\app\model\Category;
use plugin\admin\app\controller\Crud;
use support\exception\BusinessException;

/**
 * 分类管理 
 */
class CategoryController extends Crud
{
    /**
     * @var Category
     */
    protected $model = null;

    /**
     * 构造函数
     * @return void
     */
    public function __construct()
    {
        $this->model = new Category;
    }
    
    /**
     * 浏览
     * @return Response
     */
    public function index(): Response
    {
        return view('category/index');
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
        return view('category/insert');
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
        return view('category/update');
    }
}
