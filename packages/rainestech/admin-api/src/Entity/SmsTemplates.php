<?php

namespace Rainestech\AdminApi\Entity;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * Rainestech\AdminApi\Entity\SmsTemplates
 *
 * @property int $id
 * @property string $template
 * @property string $subject
 * @property string $name
 * @property int|null $editor
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|SmsTemplates newModelQuery()
 * @method static Builder|SmsTemplates newQuery()
 * @method static Builder|SmsTemplates query()
 * @method static Builder|SmsTemplates whereCreatedAt($value)
 * @method static Builder|SmsTemplates whereEditor($value)
 * @method static Builder|SmsTemplates whereId($value)
 * @method static Builder|SmsTemplates whereName($value)
 * @method static Builder|SmsTemplates whereSubject($value)
 * @method static Builder|SmsTemplates whereTemplate($value)
 * @method static Builder|SmsTemplates whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|BaseModel order()
 */
class SmsTemplates extends BaseModel {
    protected $table = 'notifications_sms';
    protected $guarded = ['id'];
}
