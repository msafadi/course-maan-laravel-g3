<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group([
    'prefix' => '/admin',
    'namespace' => 'Admin',
    'as' => 'admin.'
], function() {

    Route::group([
        'prefix' => '/categories',
        'as' => 'categories.',
    ], function() {
        
        Route::get('/', 'CategoriesController@index')->name('index');
        Route::get('/create', 'CategoriesController@create')->name('create');
        Route::post('/', 'CategoriesController@store')->name('store');
        Route::get('/{id}', 'CategoriesController@edit')->name('edit');
        Route::put('/{id}', 'CategoriesController@update')->name('update');
        Route::delete('/{id}', 'CategoriesController@destroy')->name('destroy');
    });
    
    Route::get('/posts/trash', 'PostsController@trash')->name('posts.trash');
    Route::put('/posts/{id}/restore', 'PostsController@restore')->name('posts.restore');
    Route::delete('/posts/{id}/force-delete', 'PostsController@forceDelete')->name('posts.force-delete');

    Route::get('/posts/{id}/download', 'PostsController@download')->name('posts.download');
    Route::resource('/posts', 'PostsController')->names([
        //'index' => 'admin.posts.index',
        //'show' => 'admin.posts.create',
    ]);

});


Route::get('/posts', 'PostsController@index')->name('posts');
Route::get('/posts/{id}', 'PostsController@show')->name('posts.show');

Route::get('/categories', 'CategoriesController@index')->name('categories');
Route::get('/categories/{id}', 'CategoriesController@show')->name('categories.show');
