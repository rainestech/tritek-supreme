<?php

namespace Rainestech\Personnel\Entity;

use Rainestech\AdminApi\Entity\BaseModel;
use Rainestech\AdminApi\Entity\Users;

/**
 * Rainestech\Personnel\Entity\SkillSet
 *
 * @property int $id
 * @property string|null $skill
 * @property Users|null $editor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SkillSet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SkillSet newQuery()
 * @method static Builder|BaseModel order()
 * @method static \Illuminate\Database\Eloquent\Builder|SkillSet query()
 * @method static \Illuminate\Database\Eloquent\Builder|SkillSet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SkillSet whereEditor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SkillSet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SkillSet whereSkill($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SkillSet whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SkillSet extends BaseModel
{
    protected $table = 'profiles_skillset';
    protected $guarded = ['id'];
}
