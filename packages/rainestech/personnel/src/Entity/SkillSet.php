<?php

namespace Rainestech\Personnel\Entity;

use Rainestech\AdminApi\Entity\BaseModel;
use Rainestech\AdminApi\Entity\Users;

class SkillSet extends BaseModel
{
    protected $table = 'profiles_skillset';
    protected $guarded = ['id'];
}
