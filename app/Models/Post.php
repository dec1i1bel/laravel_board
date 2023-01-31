<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property array $fillable
 */
class Post extends Model
{
    /**
     * массовое заполнение полей
     *
     * @var array $fillable
     */
    protected $fillable = [
        'title',
        'content',
        'price',
        'img_preview'
    ];

    /**
     * обратная связь с моделью User
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    /**
     * обратная связь с моделью Category
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }

    /**
     * прямая связь "one-to-many" с моделью Photo
     *
     * @return HasMany
     */
    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function carts()
    {
        return $this->belongsToMany(Cart::class)
                ->withPivot('quantity')
                ->withTimestamps();
    }
}
