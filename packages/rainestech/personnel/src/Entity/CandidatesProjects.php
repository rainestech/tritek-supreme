<?php

namespace Rainestech\Personnel\Entity;

use Illuminate\Database\Eloquent\Model;
use Rainestech\AdminApi\Entity\FileStorage;
use Rainestech\AdminApi\Entity\Users;


/**
 * Rainestech\Personnel\Entity\CandidatesProjects
 *
 * @method static \Illuminate\Database\Eloquent\Builder|CandidatesProjects newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CandidatesProjects newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CandidatesProjects query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $pId
 * @property int $cId
 * @property int|null $bccId
 * @property int|null $bcpId
 * @method static \Illuminate\Database\Eloquent\Builder|CandidatesProjects whereBccId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CandidatesProjects whereBcpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CandidatesProjects whereCId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CandidatesProjects whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CandidatesProjects wherePId($value)
 */
class CandidatesProjects extends Model
{
    protected $table = 'profiles_candidates_projects';
    protected $guarded = ['id'];
    public $timestamps = false;
}
