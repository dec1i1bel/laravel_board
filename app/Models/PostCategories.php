<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Категории, в которые входит объявление
 *
 * @property array $fillable
 * @property boolean $timestamps
 */
class PostCategories extends Model
{
    use HasFactory;

    /**
     * разрешаем массовое заполнение полей
     * (mass assignment)
     *
     * @var array $fillable
     */
    public $fillable = [
        'post_id',
        'category_id'
    ];

    /**
     * @var boolean $timestamps
     */
    public $timestamps = false;
}
