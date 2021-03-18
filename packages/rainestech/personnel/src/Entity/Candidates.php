<?php

namespace Rainestech\Personnel\Entity;

use OwenIt\Auditing\Contracts\Auditable;
use Rainestech\AdminApi\Entity\BaseModel;
use Rainestech\AdminApi\Entity\FileStorage;
use Rainestech\AdminApi\Entity\Users;


/**
 * Rainestech\Personnel\Entity\Candidates
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $modified_by
 * @property string|null $role
 * @property string|null $address
 * @property string|null $city
 * @property string|null $country
 * @property string|null $skillSet
 * @property string|null $description
 * @property int $views
 * @property int|null $userId
 * @property-read Users $editor
 * @property-read \Illuminate\Database\Eloquent\Collection|FileStorage[] $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Rainestech\Personnel\Entity\Projects[] $projects
 * @property-read int|null $projects_count
 * @property-read Users|null $users
 * @method static \Illuminate\Database\Eloquent\Builder|Candidates newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Candidates newQuery()
 * @method static Builder|BaseModel order()
 * @method static \Illuminate\Database\Eloquent\Builder|Candidates query()
 * @method static \Illuminate\Database\Eloquent\Builder|Candidates whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidates whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidates whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidates whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidates whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidates whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidates whereModifiedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidates whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidates whereSkillSet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidates whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidates whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidates whereViews($value)
 * @mixin \Eloquent
 * @property string|null $title
 * @property string|null $email
 * @property string|null $avatar
 * @property string|null $name
 * @property-read \Illuminate\Database\Eloquent\Collection|FileStorage[] $docs
 * @property-read int|null $docs_count
 * @property-read mixed $file_no
 * @property-read mixed $project_no
 * @property-read Users|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Candidates whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidates whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidates whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidates whereTitle($value)
 * @property int|null $bcId
 * @method static \Illuminate\Database\Eloquent\Builder|Candidates whereBcId($value)
 * @property int|null $bcPage
 * @method static \Illuminate\Database\Eloquent\Builder|Candidates whereBcPage($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read mixed $skills
 */
class Candidates extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'profiles_candidates';
    protected $guarded = ['id'];
    protected $dateFormat = 'Y-m-d h:m:s';
    protected $with = ['user', 'projects', 'docs'];
    protected $appends = ['fileNo', 'projectNo', 'skills'];
    protected $hidden = ['skillSet'];

    public function user()
    {
//        return $this->belongsToMany(Users::class, 'profiles_recruiters_user', 'uId', 'rId');
        return $this->belongsTo(Users::class, 'userId');
    }

    public function docs()
    {
        return $this->belongsToMany(FileStorage::class, 'profiles_candidates_files', 'fId', 'cId');
    }

    public function projects()
    {
        return $this->belongsToMany(Projects::class, 'profiles_candidates_projects', 'pId', 'cId');
    }

    public function getFileNoAttribute() {
        return $this->docs->count();
    }

    public function getSkillsAttribute() {
        return explode(',,,', $this->skillSet);
    }

    public function getProjectNoAttribute() {
        return $this->projects->count();
    }

}
