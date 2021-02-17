<?php

namespace Rainestech\AdminApi\Entity;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Rainestech\AdminApi\Entity\ActivityLog
 *
 * @property int $id
 * @property string|null $ip
 * @property string|null $userAgent
 * @property string|null $requestMethod
 * @property string|null $query
 * @property string|null $url
 * @property string|null $payload
 * @property int|null $userID
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ActivityLog newModelQuery()
 * @method static Builder|ActivityLog newQuery()
 * @method static Builder|ActivityLog query()
 * @method static Builder|ActivityLog whereCreatedAt($value)
 * @method static Builder|ActivityLog whereId($value)
 * @method static Builder|ActivityLog whereIp($value)
 * @method static Builder|ActivityLog wherePayload($value)
 * @method static Builder|ActivityLog whereQuery($value)
 * @method static Builder|ActivityLog whereRequestMethod($value)
 * @method static Builder|ActivityLog whereUpdatedAt($value)
 * @method static Builder|ActivityLog whereUrl($value)
 * @method static Builder|ActivityLog whereUserAgent($value)
 * @method static Builder|ActivityLog whereUserID($value)
 * @mixin Eloquent
 */
class ActivityLog extends Model {
    protected $table = 'security_activity_log';
    protected $guarded = ['id'];
}
