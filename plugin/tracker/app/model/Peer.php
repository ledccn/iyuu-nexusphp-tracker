<?php

namespace plugin\tracker\app\model;

use plugin\admin\app\model\Base;

/**
 * @property integer $id (主键)
 * @property integer $torrent
 * @property mixed $peer_id
 * @property string $ip
 * @property integer $port
 * @property integer $uploaded
 * @property integer $downloaded
 * @property integer $to_go
 * @property string $seeder
 * @property string $started
 * @property string $last_action
 * @property string $prev_action
 * @property string $connectable
 * @property integer $userid
 * @property string $agent
 * @property integer $finishedat
 * @property integer $downloadoffset
 * @property integer $uploadoffset
 * @property mixed $passkey
 * @property string $ipv4
 * @property string $ipv6
 * @property integer $is_seed_box
 */
class Peer extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'peers';

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
