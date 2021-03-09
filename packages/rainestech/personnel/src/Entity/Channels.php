<?php

namespace Rainestech\Personnel\Entity;

use Rainestech\AdminApi\Entity\BaseModel;
use Rainestech\AdminApi\Entity\Users;

/**
 * Rainestech\Personnel\Entity\Channels
 *
 * @property-read Users $editor
 * @property-read Users $leader
 * @property-read \Illuminate\Database\Eloquent\Collection|Users[] $members
 * @property-read int|null $members_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Channels[] $teams
 * @property-read int|null $teams_count
 * @method static \Illuminate\Database\Eloquent\Builder|Channels newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Channels newQuery()
 * @method static Builder|BaseModel order()
 * @method static \Illuminate\Database\Eloquent\Builder|Channels query()
 * @mixin \Eloquent
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $name
 * @property string|null $description
 * @property int|null $leaderId
 * @property int|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder|Channels whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channels whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channels whereEditor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channels whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channels whereLeaderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channels whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channels whereParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channels whereUpdatedAt($value)
 * @property int|null $parentId
 * @method static \Illuminate\Database\Eloquent\Builder|Channels whereParentId($value)
 */
class Channels extends BaseModel
{
    protected $table = 'profiles_channels';
    protected $guarded = ['id'];
    protected $dateFormat = 'Y-m-d h:m:s';

    protected $with = ['members', 'leader', 'teams'];

    public function members()
    {
        return $this->belongsToMany(Users::class, 'profiles_candidates_channels', 'userId', 'channelId');
    }

    public function teams()
    {
        return $this->hasMany(Channels::class, 'parentId');
    }

    public function leader() {
        return $this->belongsTo(Users::class, 'leaderId');
    }
}
