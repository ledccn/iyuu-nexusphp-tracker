<?php

namespace plugin\tracker\app\model;

use plugin\admin\app\model\Base;

/**
 * @property integer $family_id
 * @property string $name
 * @property string $peer_id
 * @property string $agent
 * @property string $comment
 * @property integer $id (主键)
 */
class AgentAllowedException extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agent_allowed_exception';

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
