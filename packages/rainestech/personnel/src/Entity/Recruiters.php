<?php

namespace Rainestech\Personnel\Entity;

use Rainestech\AdminApi\Entity\BaseModel;
use Rainestech\AdminApi\Entity\FileStorage;
use Rainestech\AdminApi\Entity\Users;


/**
 * Rainestech\Personnel\Entity\Recruiters
 *
 * @property int $id
 * @property string|null $companyName
 * @property string|null $description
 * @property string|null $companyEmail
 * @property string|null $address
 * @property string|null $website
 * @property string|null $industry
 * @property string|null $country
 * @property string|null $city
 * @property int|null $fsId
 * @property int|null $userId
 * @property int|null $modified_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|FileStorage[] $candidates
 * @property-read int|null $candidates_count
 * @property-read Users $editor
 * @property-read \Illuminate\Database\Eloquent\Collection|FileStorage[] $files
 * @property-read int|null $files_count
 * @property-read FileStorage|null $logo
 * @property-read Users|null $users
 * @method static \Illuminate\Database\Eloquent\Builder|Recruiters newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Recruiters newQuery()
 * @method static Builder|BaseModel order()
 * @method static \Illuminate\Database\Eloquent\Builder|Recruiters query()
 * @method static \Illuminate\Database\Eloquent\Builder|Recruiters whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruiters whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruiters whereCompanyEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruiters whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruiters whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruiters whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruiters whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruiters whereFsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruiters whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruiters whereIndustry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruiters whereModifiedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruiters whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruiters whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruiters whereWebsite($value)
 * @mixin \Eloquent
 */
class Recruiters extends BaseModel
{
    protected $table = 'profiles_recruiters';
    protected $guarded = ['id'];
//    protected $dates = ['joinDate', 'dob'];
    protected $dateFormat = 'Y-m-d h:m:s';

    protected $appends = ['fileNo', 'candNo'];
    protected $with = ['user', 'logo'];

    public function users()
    {
//        return $this->belongsToMany(Users::class, 'profiles_recruiters_user', 'uId', 'rId');
        return $this->belongsTo(Users::class, 'userId');
    }

    public function docs()
    {
        return $this->belongsToMany(FileStorage::class, 'profiles_recruiters_files', 'fId', 'rId');
    }

    public function candidates()
    {
        return $this->belongsToMany(FileStorage::class, 'profiles_recruiters_candidates', 'cId', 'rId');
    }

    public function logo()
    {
        return $this->belongsTo(FileStorage::class, 'fsId');
    }

    public function getFileNoAttribute() {
        return $this->docs->count();
    }

    public function getCandNoAttribute() {
        return $this->candidates->count();
    }

}
