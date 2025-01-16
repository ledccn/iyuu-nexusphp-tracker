<?php

namespace plugin\tracker\app\model;

use plugin\admin\app\model\Base;

/**
 * 种子分类
 * @property integer $id (主键)
 * @property integer $mode 
 * @property string $class_name 
 * @property string $name 
 * @property string $image 
 * @property integer $sort_index 
 * @property integer $icon_id
 */
class Category extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
