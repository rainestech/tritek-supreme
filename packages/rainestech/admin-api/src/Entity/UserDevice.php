<?php

namespace Rainestech\AdminApi\Entity;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Rainestech\AdminApi\Entity\UserDevice
 *
 * @property int $id
 * @property string|null $ip
 * @property string|null $city
 * @property string|null $country
 * @property string|null $device
 * @property string|null $deviceRaw
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string|null $browser
 * @property string|null $token
 * @property string|null $lastLogin
 * @property int $hotlist
 * @property int $userID
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|UserDevice newModelQuery()
 * @method static Builder|UserDevice newQuery()
 * @method static Builder|UserDevice query()
 * @method static Builder|UserDevice whereBrowser($value)
 * @method static Builder|UserDevice whereCity($value)
 * @method static Builder|UserDevice whereCountry($value)
 * @method static Builder|UserDevice whereCreatedAt($value)
 * @method static Builder|UserDevice whereDevice($value)
 * @method static Builder|UserDevice whereHotlist($value)
 * @method static Builder|UserDevice whereId($value)
 * @method static Builder|UserDevice whereIp($value)
 * @method static Builder|UserDevice whereLastLogin($value)
 * @method static Builder|UserDevice whereLatitude($value)
 * @method static Builder|UserDevice whereLongitude($value)
 * @method static Builder|UserDevice whereToken($value)
 * @method static Builder|UserDevice whereUpdatedAt($value)
 * @method static Builder|UserDevice whereUserID($value)
 * @mixin Eloquent
 * @method static Builder|UserDevice whereDeviceRaw($value)
 */
class UserDevice extends Model {
    protected $table = 'admin_device_location';
    protected $guarded = ['id'];
}
