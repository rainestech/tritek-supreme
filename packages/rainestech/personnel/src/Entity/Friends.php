<?php

namespace Rainestech\Personnel\Entity;

use Rainestech\AdminApi\Entity\BaseModel;
use Rainestech\AdminApi\Entity\Users;


/**
 * Rainestech\Personnel\Entity\Friends
 *
 * @property-read Users $editor
 * @property-read Users $friend
 * @method static \Illuminate\Database\Eloquent\Builder|Friends newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Friends newQuery()
 * @method static Builder|BaseModel order()
 * @method static \Illuminate\Database\Eloquent\Builder|Friends query()
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
