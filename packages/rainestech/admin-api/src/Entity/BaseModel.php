<?php


namespace Rainestech\AdminApi\Entity;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

/**
 * Rainestech\AdminApi\Entity\BaseModel
 *
 * @property-read Users $editor
 * @method static Builder|BaseModel newModelQuery()
 * @method static Builder|BaseModel newQuery()
 * @method static Builder|BaseModel query()
 * @mixin Eloquent
 * @method static Builder|BaseModel order()
 */
class BaseModel extends Model
{
    protected $hidden = ['pivot'];
    protected $with = ['editor'];
    /**
     * Indicates whether attributes are snake cased on arrays.
     *
     * @var bool
     */
    public static $snakeAttributes = false;

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            if ($model->hasAttribute('month')) {
                $model->setAttribute('month', Carbon::parse($model->txDate)->monthName);
                $model->setAttribute('year', Carbon::parse($model->txDate)->year);
            }

            if ($model->hasAttribute('editor')) {
                $model->setAttribute('editor', Auth::id());
            }
        });

        self::saving(function ($model) {
            if ($model->hasAttribute('month')) {
                $model->setAttribute('month', Carbon::parse($model->txDate)->monthName);
                $model->setAttribute('year', Carbon::parse($model->txDate)->year);
            }
            if ($model->hasAttribute('editor')) {
                $model->setAttribute('editor', Auth::id());
            }
        });

        self::updating(function ($model) {
            if ($model->hasAttribute('month')) {
                $model->setAttribute('month', Carbon::parse($model->txDate)->monthName);
                $model->setAttribute('year', Carbon::parse($model->txDate)->year);
            }
            if ($model->hasAttribute('editor')) {
                $model->setAttribute('editor', Auth::id());
            }
        });
    }

    public function editor() {
        return $this->belongsTo(Users::class, 'editor');
    }

//    public function hasAttribute($attr) {
//        return array_key_exists($attr, $this->attributes);
//    }

    public function hasAttribute($attr)
    {
        return Schema::hasColumn($this->getTable(), $attr);
    }

//    /**
//     * Get an attribute from the model.
//     *
//     * @param  string  $key
//     * @return mixed
//     */
//    public function getAttribute($key)
//    {
//        if (array_key_exists($key, $this->relations)) {
//            return parent::getAttribute($key);
//        } else {
//            return parent::getAttribute(Str::snake($key));
//        }
//    }
//
//    /**
//     * Set a given attribute on the model.
//     *
//     * @param  string  $key
//     * @param  mixed  $value
//     * @return mixed
//     */
//    public function setAttribute($key, $value)
//    {
//        return parent::setAttribute(Str::snake($key), $value);
//    }

    /**
     * Fill the model with an array of attributes.
     *
     * @param array $attributes
     * @return $this
     *
     */
    public function fill(array $attributes)
    {
        foreach ($this->fillableFromArray($attributes) as $key => $value) {

            // The developers may choose to place some attributes in the "fillable" array
            // which means only those attributes may be set through mass assignment to
            // the model, and all others will just get ignored for security reasons.
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
            }
        }

        return $this;
    }

    public function saveAndRefresh(array $options = [])
    {
        parent::save($options);
        if (isset($this->with)) {
            return $this->load($this->with);
        }

        return $this;
    }

    public function loadWith()
    {
        if (isset($this->with)) {
            return $this->load($this->with);
        }

        return $this;
    }

    public function scopeOrder($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
