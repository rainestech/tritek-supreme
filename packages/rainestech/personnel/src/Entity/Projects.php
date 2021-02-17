<?php

namespace Rainestech\Personnel\Entity;

use Rainestech\AdminApi\Entity\BaseModel;
use Rainestech\AdminApi\Entity\FileStorage;
use Rainestech\AdminApi\Entity\Users;


/**
 * Rainestech\Personnel\Entity\Projects
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $modified_by
 * @property string|null $name
 * @property string|null $description
 * @property string|null $details
 * @property int|null $leader
 * @property string|null $deadline
 * @property float|null $progress
 * @property string|null $owner
 * @property float|null $cost
 * @property int|null $totalHours
 * @property-read \Illuminate\Database\Eloquent\Collection|Users[] $candidates
 * @property-read int|null $candidates_count
 * @property-read Users $editor
 * @property-read \Illuminate\Database\Eloquent\Collection|FileStorage[] $files
 * @property-read int|null $files_count
 * @method static \Illuminate\Database\Eloquent\Builder|Projects newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Projects newQuery()
 * @method static Builder|BaseModel order()
 * @method static \Illuminate\Database\Eloquent\Builder|Projects query()
 * @method static \Illuminate\Database\Eloquent\Builder|Projects whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projects whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projects whereDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projects whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projects whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projects whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projects whereLeader($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projects whereModifiedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projects whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projects whereOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projects whereProgress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projects whereTotalHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projects whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Projects extends BaseModel
{
    protected $table = 'projects';
    protected $guarded = ['id'];
    protected $dateFormat = 'Y-m-d h:m:s';

    protected $with = ['candidates', 'docs', 'leader'];

    public function candidates()
    {
        return $this->belongsToMany(Users::class, 'profile_candidates_projects', 'cId', 'pId');
    }

    public function docs()
    {
        return $this->belongsToMany(FileStorage::class, 'projects_files', 'fId', 'pId');
    }

    public function leader() {
        return $this->belongsTo(Users::class, 'leaderId');
    }
}
