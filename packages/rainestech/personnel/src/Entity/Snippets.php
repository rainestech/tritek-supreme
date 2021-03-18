<?php

namespace Rainestech\Personnel\Entity;

use Illuminate\Database\Eloquent\Model;
use Rainestech\AdminApi\Entity\FileStorage;
use Rainestech\AdminApi\Entity\Users;

/**
 * Rainestech\Personnel\Entity\Snippets
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $snippet
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Snippets newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Snippets newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Snippets query()
 * @method static \Illuminate\Database\Eloquent\Builder|Snippets whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Snippets whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Snippets whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Snippets whereSnippet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Snippets whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Snippets extends Model
{
    protected $table = 'profiles_snippets';
    protected $guarded = ['id'];
}
