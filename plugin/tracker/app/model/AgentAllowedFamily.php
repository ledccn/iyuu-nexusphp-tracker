<?php

namespace plugin\tracker\app\model;

use plugin\admin\app\model\Base;

/**
 * @property integer $id (主键)
 * @property string $family
 * @property string $start_name
 * @property string $peer_id_pattern
 * @property integer $peer_id_match_num
 * @property string $peer_id_matchtype
 * @property string $peer_id_start
 * @property string $agent_pattern
 * @property integer $agent_match_num
 * @property string $agent_matchtype
 * @property string $agent_start
 * @property string $exception
 * @property string $allowhttps
 * @property string $comment
 * @property integer $hits
 */
class AgentAllowedFamily extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agent_allowed_family';

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
