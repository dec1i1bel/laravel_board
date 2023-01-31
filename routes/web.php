<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CartController;
use App\Models\Post;
use App\Lib\DemoService\DemoContainerRegistered;
use App\Lib\DemoService\DemoContainerBoot;
use App\Lib\DemoService\DemoCategoriesList;

// демо Service Container и Service Provider
Route::get('/demosc', function(DemoContainerRegistered $service) {
    return $service->printMessage() . '. class: ' . get_class($service);
});
Route::get('reqsc', function(Post $posts) {
    $posts = $posts->limit(3)->get();
    foreach ($posts as $post) {
        echo '<ul>';
        echo '<li>id: '.$post->id.'</li>';
        echo '<li>title: '.$post->title.'</li>';
        echo '<li>content: '.$post->content.'</li>';
        echo '<li>price: '.$post->price.'</li>';
        echo '</ul>';
    }
});
Route::get('/demob', function(DemoContainerBoot $service) {
    return $service->getPhotos();
});
Route::get('/democl', function (DemoCategoriesList $service) {
    return $service->listCategoriesHtml();
});
Route::get('/democv', function() {
    return view('demo');
});

Route::get('/cart', [CartController::class, 'index'])->name('cartIndex');
Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cartCheckout');
Route::post('/cart/add/{id}', [CartController::class, 'addPost'])
    ->name('cartAddPost');

Route::get('/', [PostsController::class, 'index'])->name('index');
Route::get('/categories', [CategoriesController::class, 'index' ])->name('categories');
Route::get('/categories/{category}', [CategoriesController::class, 'renderCategoryItemsList'])
    ->name('category_items_list');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/home/add', [HomeController::class, 'renderAddPostForm'])->name('post.add');
Route::post('/home', [HomeController::class, 'storePost'])->name('post.store');
Route::get('/{post}', [PostsController::class, 'detail'])->name('detail');

Route::get('/home/{post}/edit', [HomeController::class, 'renderEditPostForm'])->name('post.edit')
    ->middleware('can:update,post');

Route::patch('/home/{post}', [HomeController::class, 'updatePost'])->name('post.update')
    ->middleware('can:update,post');

Route::get('/home/{post}/delete', [HomeController::class, 'renderDeletePostForm'])->name('post.delete')
    ->middleware('can:destroy,post');

Route::delete('/home/{post}', [HomeController::class, 'destroyPost'])->name('post.destroy')
    ->middleware('can:destroy,post');

// неявная привязка модели к маршруту
Route::get('/modelbinding/posts/{post}', function(Post $post) {
    return view('detail1', compact('post'));
});

// явная привязка модели к маршруту через App\ServiceProviders\RouteServiceProvider
Route::get('modelbinding-2/posts/{post2}', function(Post $post2) {
    return view('detail2', compact('post2'));
});
