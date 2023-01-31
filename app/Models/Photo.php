<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Models\Post;

/**
 * @property boolean $timestamps
 * @property array $fillable
 */
class Photo extends Model
{
    use HasFactory;

    /**
     * отключаем обращение к полям created_at и updated_at при записи
     *
     * @var boolean $timestamps
     */
    public $timestamps = false;

    /**
     * разрешаем массовое (mass assignment) заполнение полей
     *
     * @var array $fillable
     */
    public $fillable = [
        'file',
        'post_id',
        'photo_num'
    ];

    /**
     * обратная связь с моделью Post
     *
     * @return connection
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
