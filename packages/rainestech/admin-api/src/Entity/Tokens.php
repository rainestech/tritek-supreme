<?php

namespace Rainestech\AdminApi\Entity;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Rainestech\AdminApi\Entity\Tokens
 *
 * @property int $id
 * @property string|null $ip
 * @property string|null $token
 * @property string|null $device
 * @property int|null $userID
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Tokens newModelQuery()
 * @method static Builder|Tokens newQuery()
 * @method static Builder|Tokens query()
 * @method static Builder|Tokens whereCreatedAt($value)
 * @method static Builder|Tokens whereDevice($value)
 * @method static Builder|Tokens whereId($value)
 * @method static Builder|Tokens whereIp($value)
 * @method static Builder|Tokens whereToken($value)
 * @method static Builder|Tokens whereUpdatedAt($value)
 * @method static Builder|Tokens whereUserID($value)
 * @mixin Eloquent
 */
class Tokens extends Model {
    protected $guarded = ['id'];
    protected $table = 'security_tokens';
}
