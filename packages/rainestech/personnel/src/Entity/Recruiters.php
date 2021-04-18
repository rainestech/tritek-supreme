<?php

namespace Rainestech\Personnel\Entity;

use OwenIt\Auditing\Contracts\Auditable;
use Rainestech\AdminApi\Entity\BaseModel;
use Rainestech\AdminApi\Entity\Documents;
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
 * @property string|null $email
 * @property string|null $type
 * @property string|null $size
 * @property-read \Illuminate\Database\Eloquent\Collection|FileStorage[] $docs
 * @property-read int|null $docs_count
 * @property-read mixed $cand_no
 * @property-read mixed $file_no
 * @property-read mixed $name
 * @property-read Users|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Recruiters whereEditor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruiters whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruiters whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruiters whereType($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 */
class Recruiters extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'profiles_recruiters';
    protected $guarded = ['id'];
//    protected $dates = ['joinDate', 'dob'];
    protected $dateFormat = 'Y-m-d h:m:s';

    protected $appends = ['fileNo', 'candNo', 'name'];
    protected $with = ['user', 'logo'];

    public function user()
    {
//        return $this->belongsToMany(Users::class, 'profiles_recruiters_user', 'uId', 'rId');
        return $this->belongsTo(Users::class, 'userId');
    }

    public function candidates()
    {
        return $this->belongsToMany(Candidates::class, 'profiles_recruiters_candidates', 'rId', 'cId')
            ->withTimestamps();
    }

    public function logo()
    {
        return $this->belongsTo(FileStorage::class, 'fsId');
    }

    public function getFileNoAttribute() {
        if ($this->user) {
            return $this->user->docs->count();
        }

        return 0;
    }

    public function getNameAttribute() {
        return $this->companyName;
    }

    public function getCandNoAttribute() {
        return $this->candidates->count();
    }

}
