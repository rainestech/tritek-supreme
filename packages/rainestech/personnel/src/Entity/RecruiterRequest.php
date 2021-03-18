<?php

namespace Rainestech\Personnel\Entity;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Rainestech\Personnel\Entity\RecruiterRequest
 *
 * @property int $id
 * @property int|null $rId
 * @property int|null $cId
 * @property int|null $approved
 * @property int|null $editor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \Rainestech\Personnel\Entity\Candidates|null $candidate
 * @property-read \Rainestech\Personnel\Entity\Recruiters|null $recruiter
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterRequest whereApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterRequest whereCId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterRequest whereEditor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterRequest whereRId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterRequest whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RecruiterRequest extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'profiles_recruiters_request';
    protected $guarded = ['id'];

    public function candidate() {
        return $this->belongsTo(Candidates::class, 'cId');
    }

    public function recruiter() {
        return $this->belongsTo(Recruiters::class, 'rId');
    }
}
