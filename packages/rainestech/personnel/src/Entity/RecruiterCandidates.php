<?php

namespace Rainestech\Personnel\Entity;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Rainestech\Personnel\Entity\RecruiterCandidates
 *
 * @property int $id
 * @property int $rid
 * @property int $cid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterCandidates newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterCandidates newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterCandidates query()
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterCandidates whereCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterCandidates whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterCandidates whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterCandidates whereRid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterCandidates whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $approved
 * @property int $requested
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterCandidates whereApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterCandidates whereRequested($value)
 * @property-read \Rainestech\Personnel\Entity\Candidates $candidate
 * @property-read \Rainestech\Personnel\Entity\Recruiters $recruiter
 */
class RecruiterCandidates extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'profiles_recruiters_candidates';
    protected $guarded = ['id'];
    protected $casts = [
        'requested' => 'boolean',
        'approved' => 'boolean',
    ];

    public function candidate() {
        return $this->belongsTo(Candidates::class, 'cid');
    }

    public function recruiter() {
        return $this->belongsTo(Recruiters::class, 'rid');
    }
}
