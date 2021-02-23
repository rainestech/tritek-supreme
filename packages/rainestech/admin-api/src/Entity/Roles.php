<?php

namespace Rainestech\AdminApi\Entity;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

/**
 * Rainestech\AdminApi\Entity\Roles
 *
 * @property int $id
 * @property string $role
 * @property int|null $defaultRole
 * @property Users|null $editor
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read mixed $status
 * @property-read Collection|Privilege[] $privileges
 * @property-read int|null $privileges_count
 * @property-read Collection|Users[] $users
 * @property-read int|null $users_count
 * @method static Builder|Roles newModelQuery()
 * @method static Builder|Roles newQuery()
 * @method static Builder|Roles query()
 * @method static Builder|Roles whereCreatedAt($value)
 * @method static Builder|Roles whereDefaultRole($value)
 * @method static Builder|Roles whereEditor($value)
 * @method static Builder|Roles whereId($value)
 * @method static Builder|Roles whereRole($value)
 * @method static Builder|Roles whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|BaseModel order()
 */
class Roles extends BaseModel
{
    protected $table = 'admin_roles';
    protected $guarded = ['id'];
    protected $casts = [
        'defaultRole' => 'boolean'
    ];
    protected $with = [];


    public function privileges()
    {
        return $this->belongsToMany(Privilege::class, 'admin_roles_privileges', 'role_id', 'privilege_id');
    }

    public function users()
    {
        return $this->belongsToMany(Users::class, 'admin_user_role', 'user_id', 'role_id');
    }

    public function editor() {
        return $this->belongsTo(Users::class, 'editor');
    }

    public function getStatusAttribute() {
        return boolval($this->status);
    }
}
