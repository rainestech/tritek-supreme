<?php


namespace Rainestech\Personnel\Entity;


use Rainestech\AdminApi\Entity\BaseModel;
use Rainestech\AdminApi\Entity\Users;

/**
 * Rainestech\Personnel\Entity\RecruitersPage
 *
 * @property-read Users $editor
 * @property-read \Rainestech\Personnel\Entity\Recruiters $recruiter
 * @method static \Illuminate\Database\Eloquent\Builder|RecruitersPage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecruitersPage newQuery()
 * @method static Builder|BaseModel order()
 * @method static \Illuminate\Database\Eloquent\Builder|RecruitersPage query()
 * @mixin \Eloquent
 */
class RecruitersPage extends BaseModel
{
    protected $table = 'profiles_recruiters_page';
    protected $guarded = ['id'];
//    protected $dates = ['joinDate', 'dob'];
    protected $dateFormat = 'Y-m-d h:m:s';

//    protected $appends = ['name', 'states'];

    protected $with = ['recruiter'];

    public function recruiter()
    {
        return $this->belongsTo(Recruiters::class, 'rId');
    }

}
