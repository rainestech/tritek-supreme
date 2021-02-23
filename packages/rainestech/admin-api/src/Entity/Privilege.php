<?php

namespace Rainestech\AdminApi\Entity;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Rainestech\AdminApi\Entity\Privilege
 *
 * @property int $id
 * @property string|null $app
 * @property string|null $module
 * @property string|null $privilege
 * @property string|null $url
 * @property string|null $name
 * @property string|null $icon
 * @property int|null $orderNo
 * @property int $hasChildren
 * @property Users|null $editor
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Roles[] $roles
 * @property-read int|null $roles_count
 * @method static Builder|Privilege newModelQuery()
 * @method static Builder|Privilege newQuery()
 * @method static Builder|Privilege query()
 * @method static Builder|Privilege whereApp($value)
 * @method static Builder|Privilege whereCreatedAt($value)
 * @method static Builder|Privilege whereEditor($value)
 * @method static Builder|Privilege whereHasChildren($value)
 * @method static Builder|Privilege whereIcon($value)
 * @method static Builder|Privilege whereId($value)
 * @method static Builder|Privilege whereModule($value)
 * @method static Builder|Privilege whereName($value)
 * @method static Builder|Privilege whereOrderNo($value)
 * @method static Builder|Privilege wherePrivilege($value)
 * @method static Builder|Privilege whereUpdatedAt($value)
 * @method static Builder|Privilege whereUrl($value)
 * @mixin Eloquent
 * @property int|null $onNav
 * @method static Builder|Privilege whereOnNav($value)
 */
class Privilege extends Model {
    protected $table = 'admin_privilege';
    protected $guarded = ['id'];
    protected $casts = ['hasChildren' => 'boolean', 'onNav' => 'boolean'];

//    public function roles() {
//        return $this->belongsToMany(Roles::class, 'admin_role_privilege', 'privilege_id', 'role_id');
//    }

    public function editor() {
        return $this->belongsTo(Users::class, 'editor');
    }
}
