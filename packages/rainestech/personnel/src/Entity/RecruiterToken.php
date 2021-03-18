<?php

namespace Rainestech\Personnel\Entity;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Rainestech\Personnel\Entity\RecruiterToken
 *
 * @property int $id
 * @property string|null $token
 * @property string|null $issuedDate
 * @property int|null $useCount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $userId
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterToken whereIssuedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterToken whereUseCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterToken whereUserId($value)
 * @mixin \Eloquent
 */
class RecruiterToken extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'profiles_recruiters_token';
    protected $guarded = ['id'];
}
