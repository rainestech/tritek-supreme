<?php

namespace Rainestech\AdminApi\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * Rainestech\AdminApi\Entity\Documents
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property int|null $private
 * @property int|null $editor
 * @property int|null $fileId
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Rainestech\AdminApi\Entity\FileStorage|null $file
 * @property-read \Rainestech\AdminApi\Entity\Users|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Documents newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Documents newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Documents query()
 * @method static \Illuminate\Database\Eloquent\Builder|Documents whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Documents whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Documents whereEditor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Documents whereFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Documents whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Documents whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Documents wherePrivate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Documents whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Documents extends Model {
    protected $table = 'admin_documents';
    protected $guarded = ['id'];
    protected $with = ['file'];

    public function user() {
        return $this->belongsTo(Users::class, 'editor');
    }

    public function file() {
        return $this->belongsTo(FileStorage::class, 'fileId');
    }

    public function loadWith()
    {
        if (isset($this->with)) {
            return $this->load($this->with);
        }

        return $this;
    }
}
