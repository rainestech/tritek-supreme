<?php

namespace Rainestech\Personnel\Entity;

use Rainestech\AdminApi\Entity\BaseModel;
use Rainestech\AdminApi\Entity\Users;


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
