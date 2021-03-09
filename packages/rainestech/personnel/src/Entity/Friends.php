<?php

namespace Rainestech\Personnel\Entity;

use Rainestech\AdminApi\Entity\BaseModel;
use Rainestech\AdminApi\Entity\Users;


/**
 * Rainestech\Personnel\Entity\Friends
 *
 * @property int $id
 * @property int $uId
 * @property int $fId
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Users $editor
 * @property-read Users $friend
 * @method static \Illuminate\Database\Eloquent\Builder|Friends newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Friends newQuery()
 * @method static Builder|BaseModel order()
 * @method static \Illuminate\Database\Eloquent\Builder|Friends query()
 * @method static \Illuminate\Database\Eloquent\Builder|Friends whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Friends whereFId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Friends whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Friends whereUId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Friends whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Friends extends BaseModel
{
    protected $table = 'chats_friends';
    protected $guarded = ['id'];
    protected $dateFormat = 'Y-m-d h:m:s';
    protected $with = ['friend'];

    public function friend()
    {
        return $this->belongsTo(Users::class, 'fId');
    }
}
