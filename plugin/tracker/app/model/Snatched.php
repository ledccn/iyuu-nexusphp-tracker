<?php

namespace plugin\tracker\app\model;

use plugin\admin\app\model\Base;

/**
 * @property integer $id (主键)
 * @property integer $torrentid
 * @property integer $userid
 * @property string $ip
 * @property integer $port
 * @property integer $uploaded
 * @property integer $downloaded
 * @property integer $to_go
 * @property integer $seedtime
 * @property integer $leechtime
 * @property string $last_action
 * @property string $startdat
 * @property string $completedat
 * @property string $finished
 */
class Snatched extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'snatched';

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
