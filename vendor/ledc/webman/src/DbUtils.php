<?php

namespace Ledc\Webman;

use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Builder;
use support\Db;

/**
 * 数据库工具类
 */
class DbUtils
{
    /**
     * 获取webman-admin数据库连接
     * @return Connection
     */
    public static function db(): Connection
    {
        return Db::connection('plugin.admin.mysql');
    }

    /**
     * 获取SchemaBuilder
     * @return Builder
     */
    public static function schema(): Builder
    {
        return Db::schema('plugin.admin.mysql');
    }
}
