<?php

namespace Rainestech\AdminApi\Entity;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Rainestech\AdminApi\Entity\LoginLog
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $user_id
 * @method static Builder|LoginLog newModelQuery()
 * @method static Builder|LoginLog newQuery()
 * @method static Builder|LoginLog query()
 * @method static Builder|LoginLog whereCreatedAt($value)
 * @method static Builder|LoginLog whereId($value)
 * @method static Builder|LoginLog whereUpdatedAt($value)
 * @method static Builder|LoginLog whereUserId($value)
 * @mixin Eloquent
 * @property int|null $userId
 */
class LoginLog extends Model {
    protected $table = 'admin_login_log';
    protected $guarded = ['id'];
}
