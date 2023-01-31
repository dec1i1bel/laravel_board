<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {
    $router->get('laravel_admin_dashboard', 'HomeController@index')->name('home');
    $router->get('/', 'LboardHomeController@index')->name('lboard_home');
    $router->resource('posts', '\App\Admin\Controllers\LboardPostsController');
});
