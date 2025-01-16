<?php

namespace plugin\tracker\app\model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Iyuu\Tracker\Cache\TorrentsCache;
use plugin\admin\app\model\Base;

/**
 * @property integer $id (主键)
 * @property mixed $info_hash
 * @property string $name
 * @property string $filename
 * @property string $save_as
 * @property string $cover
 * @property mixed $descr
 * @property string $small_descr
 * @property mixed $ori_descr
 * @property integer $category
 * @property integer $source
 * @property integer $medium
 * @property integer $codec
 * @property integer $standard
 * @property integer $processing
 * @property integer $team
 * @property integer $audiocodec
 * @property integer $size
 * @property string $added
 * @property string $type
 * @property integer $numfiles
 * @property integer $comments
 * @property integer $views
 * @property integer $hits
 * @property integer $times_completed
 * @property integer $leechers
 * @property integer $seeders
 * @property string $last_action
 * @property string $visible
 * @property string $banned
 * @property integer $owner
 * @property mixed $nfo
 * @property integer $sp_state
 * @property integer $promotion_time_type
 * @property string $promotion_until
 * @property string $anonymous
 * @property integer $url
 * @property string $pos_state
 * @property string $pos_state_until
 * @property integer $cache_stamp
 * @property string $picktype
 * @property string $picktime
 * @property string $last_reseed
 * @property mixed $pt_gen
 * @property string $technical_info
 * @property integer $hr
 * @property integer $approval_status
 * @property integer $price
 * @property mixed $pieces_hash
 */
class Torrent extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'torrents';

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

    /**
     * 种子审核状态：待审核
     */
    public const int APPROVAL_STATUS_NONE = 0;
    /**
     * 种子审核状态：已通过
     */
    public const int APPROVAL_STATUS_ALLOW = 1;
    /**
     * 种子审核状态：已拒绝
     */
    public const int APPROVAL_STATUS_DENY = 2;

    /**
     * 凭种子info_hash查询
     * @param string $info_hash
     * @return static|null
     */
    public static function queryByInfoHash(string $info_hash): ?static
    {
        return self::query()->where('info_hash', $info_hash)
            ->select(['id', 'size', 'owner', 'sp_state', 'seeders', 'leechers', 'category', 'added', 'banned', 'hr', 'approval_status', 'price'])
            ->selectRaw('UNIX_TIMESTAMP(added) AS ts')
            ->first();
    }

    /**
     * 刷新缓存
     * @param string $info_hash
     * @param Torrent $model
     * @return void
     */
    public static function refreshCache(string $info_hash, self $model): void
    {
        // info_hash 二进制处理 bin2hax
        TorrentsCache::instance()->set($info_hash, $model->toArray());
    }

    /**
     * 【一对一关联】种子分类
     * @return HasOne
     */
    public function categories(): HasOne
    {
        return $this->hasOne(Category::class, 'id', 'category');
    }
}
