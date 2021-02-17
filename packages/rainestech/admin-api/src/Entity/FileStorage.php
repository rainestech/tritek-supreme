<?php

namespace Rainestech\AdminApi\Entity;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Rainestech\AdminApi\Entity\FileStorage
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $fileType
 * @property string|null $link
 * @property string|null $storageType
 * @property string|null $tag
 * @property string|null $objID
 * @property int|null $editor
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|FileStorage newModelQuery()
 * @method static Builder|FileStorage newQuery()
 * @method static Builder|FileStorage query()
 * @method static Builder|FileStorage whereCreatedAt($value)
 * @method static Builder|FileStorage whereEditor($value)
 * @method static Builder|FileStorage whereFileType($value)
 * @method static Builder|FileStorage whereId($value)
 * @method static Builder|FileStorage whereLink($value)
 * @method static Builder|FileStorage whereName($value)
 * @method static Builder|FileStorage whereObjID($value)
 * @method static Builder|FileStorage whereStorageType($value)
 * @method static Builder|FileStorage whereTag($value)
 * @method static Builder|FileStorage whereUpdatedAt($value)
 * @mixin Eloquent
 */
class FileStorage extends Model {
    protected $table = 'file_storage';
    protected $guarded = ['id'];
}
