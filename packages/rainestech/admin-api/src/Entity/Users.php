<?php

namespace Rainestech\AdminApi\Entity;

use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Rainestech\AdminApi\Entity\Users
 *
 * @property int $id
 * @property string|null $firstName
 * @property string|null $lastName
 * @property string $username
 * @property string $email
 * @property int $status
 * @property string|null $email_verified_at
 * @property string $password
 * @property string|null $phoneNo
 * @property int|null $passportID
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read mixed $is_admin
 * @property-read mixed $modules
 * @property-read mixed $name
 * @property-read mixed $privileges
 * @property-read mixed $roles_model
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read FileStorage|null $passport
 * @property-read Collection|Roles[] $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|Users newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Users newQuery()
 * @method static Builder|Users onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Users query()
 * @method static \Illuminate\Database\Eloquent\Builder|Users whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Users whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Users whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Users whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Users whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Users whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Users whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Users wherePassportID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Users wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Users wherePhoneNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Users whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Users whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Users whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Users whereUsername($value)
 * @method static Builder|Users withTrashed()
 * @method static Builder|Users withoutTrashed()
 * @mixin Eloquent
 * @property int $adminVerified
 * @property string|null $deleted_on
 * @property string|null $lastPwd
 * @property string|null $lastPwdChange
 * @property string|null $companyName
 * @property string|null $contactEmail
 * @method static \Illuminate\Database\Eloquent\Builder|Users whereAdminVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Users whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Users whereContactEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Users whereDeletedOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Users whereLastPwd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Users whereLastPwdChange($value)
 * @property-read mixed $role
 * @property string|null $avatar
 * @method static \Illuminate\Database\Eloquent\Builder|Users whereAvatar($value)
 * @property-read Collection|\Rainestech\AdminApi\Entity\Documents[] $docs
 * @property-read int|null $docs_count
 */
class Users extends Authenticatable implements JWTSubject
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    protected $table = 'admin_users';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     * protected $casts = [
     *     'email_verified_at' => 'datetime',
     * ];
     */

    protected $appends = ['privileges', 'role', 'name', 'pivot'];

    protected $with = ['roles', 'passport'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'pivot', 'lastPwd', 'lastPwdChange'];

    public function getIsAdminAttribute()
    {
        return true;
    }

    public function roles() {
        return $this->belongsToMany(Roles::class, 'admin_user_role', 'user_id', 'role_id');
    }

    public function docs() {
        return $this->hasMany(Documents::class, 'editor');
    }

    public function getPrivilegesAttribute() {
        $result = new Collection();
        foreach ($this->roles as $role) {
            $result = $result->merge($role->privileges);
        }

        return $result->sortBy('orderNo');
    }

    public function getNameAttribute() {
        if ($this->firstName) {
            return $this->firstName . ' ' . $this->lastName;
        }
        return $this->username;
    }

    public function getRoleAttribute() {
        if ($this->roles->count() > 0) {
            return $this->roles[0]['role'];
        }
        return '';
    }

    public function getModulesAttribute() {
        $result = new Collection();
        foreach ($this->roles as $role) {
            $result = $result->merge($role->privileges);
        }

        return $result->unique('module')->sortBy('orderNo');
    }

    public function passport() {
        return $this->belongsTo(FileStorage::class, 'passportID');
    }

    public function getRolesModelAttribute() {
        return $this->roles();
    }

    /**
     * @inheritDoc
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @inheritDoc
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
