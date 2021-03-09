<?php

namespace Rainestech\Tasks\Entity;

use Rainestech\AdminApi\Entity\BaseModel;
use Rainestech\AdminApi\Entity\FileStorage;
use Rainestech\AdminApi\Entity\Users;
use Rainestech\Personnel\Entity\Channels;


/**
 * Rainestech\Tasks\Entity\Tasks
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $name
 * @property string|null $description
 * @property int|null $channelId
 * @property string|null $priority
 * @property Tasks|null $parent
 * @property int|null $doneById
 * @property string|null $dueDate
 * @property string|null $doneDate
 * @property int|null $assignedToId
 * @property Users|null $editor
 * @property-read Users $assignedTo
 * @property-read Channels|null $channels
 * @property-read \Illuminate\Database\Eloquent\Collection|\Rainestech\Tasks\Entity\Comments[] $comments
 * @property-read int|null $comments_count
 * @property-read Users|null $createdBy
 * @property-read \Illuminate\Database\Eloquent\Collection|FileStorage[] $docs
 * @property-read int|null $docs_count
 * @property-read mixed $file_no
 * @property-read mixed $project_no
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks newQuery()
 * @method static Builder|BaseModel order()
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks whereAssignedToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks whereChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks whereDoneById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks whereDoneDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks whereEditor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks whereParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $parentId
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks whereParentId($value)
 * @property int|null $position
 * @property-read \Illuminate\Database\Eloquent\Collection|Tasks[] $children
 * @property-read int|null $children_count
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks wherePosition($value)
 */
class Tasks extends BaseModel
{
    protected $table = 'tasks_tasks';
    protected $guarded = ['id'];
    protected $dateFormat = 'Y-m-d h:m:s';
    protected $with = ['createdBy', 'assignedTo', 'children', 'comments', 'docs'];
    protected $dates = ['dueDate'];
    protected $appends = ['channelName'];

    public function createdBy()
    {
        return $this->belongsTo(Users::class, 'editor');
    }

    public function assignedTo()
    {
        return $this->belongsToMany(Users::class, 'tasks_user_task', 'userId', 'taskId');
    }

    public function parent()
    {
        return $this->belongsTo(Tasks::class, 'parentId');
    }

    public function children()
    {
        return $this->hasMany(Tasks::class, 'parentId');
    }

    public function comments()
    {
        return $this->hasMany(Comments::class, 'taskId');
    }

    public function docs()
    {
        return $this->belongsToMany(FileStorage::class, 'tasks_files', 'fileId', 'taskId');
    }

    public function channels()
    {
        return $this->belongsTo(Channels::class, 'channelId');
    }

    public function getFileNoAttribute() {
        return $this->docs->count();
    }

    public function getChannelNameAttribute() {
        return $this->channels->name;
    }

    public function getProjectNoAttribute() {
        return $this->channels->count();
    }

}
