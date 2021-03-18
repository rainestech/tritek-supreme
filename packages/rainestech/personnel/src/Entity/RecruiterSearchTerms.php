<?php

namespace Rainestech\Personnel\Entity;

use Rainestech\AdminApi\Entity\BaseModel;

class RecruiterSearchTerms extends BaseModel
{
    protected $table = 'search_terms';
    protected $guarded = ['id'];

    protected $with = ['profile'];

    public function profile()
    {
        return $this->belongsTo(Recruiters::class, 'rid');
    }
}
