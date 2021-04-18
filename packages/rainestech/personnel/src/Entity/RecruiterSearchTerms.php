<?php

namespace Rainestech\Personnel\Entity;

use Rainestech\AdminApi\Entity\BaseModel;

/**
 * Rainestech\Personnel\Entity\RecruiterSearchTerms
 *
 * @property int $id
 * @property int|null $rid
 * @property string|null $term
 * @property int $freq
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Rainestech\AdminApi\Entity\Users $editor
 * @property-read \Rainestech\Personnel\Entity\Recruiters|null $profile
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterSearchTerms newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterSearchTerms newQuery()
 * @method static Builder|BaseModel order()
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterSearchTerms query()
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterSearchTerms whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterSearchTerms whereFreq($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterSearchTerms whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterSearchTerms whereRid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterSearchTerms whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruiterSearchTerms whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
