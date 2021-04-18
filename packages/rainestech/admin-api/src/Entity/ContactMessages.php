<?php

namespace Rainestech\AdminApi\Entity;


/**
 * Rainestech\AdminApi\Entity\ContactMessages
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $message
 * @property \Rainestech\AdminApi\Entity\Users|null $editor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ContactMessages newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactMessages newQuery()
 * @method static Builder|BaseModel order()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactMessages query()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactMessages whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactMessages whereEditor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactMessages whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactMessages whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactMessages whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactMessages whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactMessages whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ContactMessages extends BaseModel {
    protected $table = 'admin_contact';
    protected $guarded = ['id'];
}
