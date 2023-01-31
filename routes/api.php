<?php

use Illuminate\Support\Facades\Route;

Route::get(
    'posts',
    [
        \App\Http\Controllers\ApiPostsController::class,
        'getAll'
    ]
);

Route::get(
    'posts/{post}',
    [
        \App\Http\Controllers\ApiPostsController::class,
        'getSingle'
    ]
);

Route::get(
    'categories',
    [
        \App\Http\Controllers\ApiCategoriesController::class,
        'getAll'
    ]
);

Route::get(
    'categories/{category}',
    [
        \App\Http\Controllers\ApiCategoriesController::class,
        'getCategory'
    ]
);

Route::get(
    'category_posts/{category}',
    [
        \App\Http\Controllers\ApiPostsController::class,
        'getCategoryPosts'
    ]
);