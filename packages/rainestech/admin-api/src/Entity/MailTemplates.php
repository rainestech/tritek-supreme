<?php

namespace Rainestech\AdminApi\Entity;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * Rainestech\AdminApi\Entity\MailTemplates
 *
 * @property int $id
 * @property string $template
 * @property string $json
 * @property string $subject
 * @property string $name
 * @property int|null $editor
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|MailTemplates newModelQuery()
 * @method static Builder|MailTemplates newQuery()
 * @method static Builder|MailTemplates query()
 * @method static Builder|MailTemplates whereCreatedAt($value)
 * @method static Builder|MailTemplates whereEditor($value)
 * @method static Builder|MailTemplates whereId($value)
 * @method static Builder|MailTemplates whereJson($value)
 * @method static Builder|MailTemplates whereName($value)
 * @method static Builder|MailTemplates whereSubject($value)
 * @method static Builder|MailTemplates whereTemplate($value)
 * @method static Builder|MailTemplates whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|BaseModel order()
 */
class MailTemplates extends BaseModel {
    protected $table = 'notifications_mail_templates';
    protected $guarded = ['id'];
}
