<?php

namespace Rainestech\Tasks\Entity;

use Rainestech\AdminApi\Entity\BaseModel;
use Rainestech\AdminApi\Entity\FileStorage;
use Rainestech\AdminApi\Entity\Users;

/**
 * Rainestech\Tasks\Entity\Comments
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $taskId
 * @property int|null $fileId
 * @property string|null $comment
 * @property Users|null $editor
 * @property-read FileStorage|null $docs
 * @property-read \Rainestech\Tasks\Entity\Tasks|null $tasks
 * @property-read Users|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Comments newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comments newQuery()
 * @method static Builder|BaseModel order()
 * @method static \Illuminate\Database\Eloquent\Builder|Comments query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comments whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comments whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comments whereEditor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comments whereFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comments whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comments whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comments whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Comments extends BaseModel
{
    protected $table = 'tasks_comments';
    protected $guarded = ['id'];
    protected $dateFormat = 'Y-m-d h:m:s';
    protected $with = ['user', 'docs'];
//    protected $appends = ['passport'];

    public function user()
    {
        return $this->belongsTo(Users::class, 'editor');
    }

    public function docs()
    {
        return $this->belongsTo(FileStorage::class, 'fileId');
    }

    public function tasks()
    {
        return $this->belongsTo(Tasks::class, 'taskId');
    }

    public function getPassportAttribute() {
        if ($this->user->passport) {
            return $this->user->passport->link;
        }

        return null;
    }
}
