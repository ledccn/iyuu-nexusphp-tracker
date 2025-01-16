<?php

namespace plugin\tracker\app\model;

use Iyuu\Tracker\Cache\PasskeyCache;
use Iyuu\Tracker\Cache\UserCache;
use plugin\admin\app\model\Base;

/**
 * @property integer $id (主键)
 * @property string $username
 * @property string $passhash
 * @property string $secret
 * @property string $email
 * @property string $status
 * @property string $added
 * @property string $last_login
 * @property string $last_access
 * @property string $last_home
 * @property string $last_offer
 * @property string $forum_access
 * @property string $last_staffmsg
 * @property string $last_pm
 * @property string $last_comment
 * @property string $last_post
 * @property integer $last_browse
 * @property integer $last_music
 * @property integer $last_catchup
 * @property string $editsecret
 * @property string $privacy
 * @property integer $stylesheet
 * @property integer $caticon
 * @property string $fontsize
 * @property string $info
 * @property string $acceptpms
 * @property string $commentpm
 * @property string $ip
 * @property integer $class
 * @property integer $max_class_once
 * @property string $avatar
 * @property integer $uploaded
 * @property integer $downloaded
 * @property integer $seedtime
 * @property integer $leechtime
 * @property string $title
 * @property integer $country
 * @property string $notifs
 * @property string $modcomment
 * @property string $enabled
 * @property string $avatars
 * @property string $donor
 * @property string $donated
 * @property string $donated_cny
 * @property string $donoruntil
 * @property string $warned
 * @property string $warneduntil
 * @property string $noad
 * @property string $noaduntil
 * @property integer $torrentsperpage
 * @property integer $topicsperpage
 * @property integer $postsperpage
 * @property string $clicktopic
 * @property string $deletepms
 * @property string $savepms
 * @property string $showhot
 * @property string $showclassic
 * @property string $support
 * @property string $picker
 * @property string $stafffor
 * @property string $supportfor
 * @property string $pickfor
 * @property string $supportlang
 * @property string $passkey
 * @property string $promotion_link
 * @property string $uploadpos
 * @property string $forumpost
 * @property string $downloadpos
 * @property integer $clientselect
 * @property string $signatures
 * @property string $signature
 * @property integer $lang
 * @property integer $cheat
 * @property integer $download
 * @property integer $upload
 * @property integer $isp
 * @property integer $invites
 * @property integer $invited_by
 * @property string $gender
 * @property string $vip_added
 * @property string $vip_until
 * @property string $seedbonus
 * @property string $charity
 * @property string $bonuscomment
 * @property string $parked
 * @property string $leechwarn
 * @property string $leechwarnuntil
 * @property string $lastwarned
 * @property integer $timeswarned
 * @property integer $warnedby
 * @property integer $sbnum
 * @property integer $sbrefresh
 * @property string $hidehb
 * @property string $showimdb
 * @property string $showdescription
 * @property string $showcomment
 * @property string $showclienterror
 * @property integer $showdlnotice
 * @property string $tooltip
 * @property string $shownfo
 * @property string $timetype
 * @property string $appendsticky
 * @property string $appendnew
 * @property string $appendpromotion
 * @property string $appendpicked
 * @property string $dlicon
 * @property string $bmicon
 * @property string $showsmalldescr
 * @property string $showcomnum
 * @property string $showlastcom
 * @property string $showlastpost
 * @property integer $pmnum
 * @property integer $school
 * @property string $showfb
 * @property string $page
 * @property string $two_step_secret
 * @property string $seed_points
 * @property string $seed_points_per_hour
 * @property integer $attendance_card
 * @property integer $offer_allowed_count
 * @property string $seed_points_updated_at
 * @property string $seed_time_updated_at
 * @property string $current_tracker
 */
class User extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

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
     * tracker所需的字段
     */
    public const array TACKER_FIELD = [
        'id',
        'username',
        'downloadpos',
        'enabled',
        'uploaded',
        'downloaded',
        'class',
        'parked',
        'clientselect',
        'showclienterror',
        'passkey',
        'donor',
        'donoruntil',
        'seedbonus'
    ];

    /**
     * @param string $passkey 用户密钥passkey
     * @return self|null
     */
    public static function queryByPasskey(string $passkey): ?self
    {
        return static::query()->where('passkey', $passkey)->select(static::TACKER_FIELD)->first();
    }

    /**
     * @param int $id 用户ID
     * @return self|null
     */
    public static function queryById(int $id): ?self
    {
        return static::query()->select(static::TACKER_FIELD)->find($id);
    }

    /**
     * 刷新缓存（passkey、user）
     * @param int $id
     * @return self
     */
    public static function refreshCacheById(int $id): self
    {
        /** @var self $model */
        $model = self::queryById($id);

        static::refreshCache($model);
        return $model;
    }

    /**
     * 刷新缓存（passkey、user）
     * @param User $model
     * @return void
     */
    public static function refreshCache(self $model): void
    {
        PasskeyCache::instance()->hSet($model->passkey, $model->id);
        UserCache::instance()->set($model->id, $model->toArray());
    }

    /**
     * 是否为捐赠者
     * @return bool
     */
    public function isDonor(): bool
    {
        return 'yes' === $this->donor && (empty($this->donoruntil) || str_contains($this->donoruntil, '0000-00-00') || date('Y-m-d H:i:s') < $this->donoruntil);
    }
}
