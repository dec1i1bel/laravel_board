<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

/**
 * @property boolean $timestamps
 */
class Category extends Model
{
    use HasFactory;

    /**
     * исключаем поля created_at и updated_at из запросов
     *
     * @var boolean $timestamps
     */
    public $timestamps = false;

    /**
     * Прямая связь "one-to-many" с моделью Post
     *
     * @return Model
     */
    public function post(): Model
    {
        return $this->hasMany(Post::class);
    }

    /**
     * получаем категории товаров
     * из таблицы post_categories
     * и их названия
     *
     * @return Collection
     */
    public static function getPostsCategories(): Collection
    {
        return DB::table('categories')
                ->leftJoin(
                'post_categories',
                    'categories.id',
                    '=',
                    'post_categories.category_id'
                )
                ->select(
                    'post_categories.post_id',
                    'categories.id',
                    'categories.name'
                )
                ->get();
    }
}
