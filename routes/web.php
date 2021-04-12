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
    
    Route::resource('/posts', 'PostsController')->names([
        //'index' => 'admin.posts.index',
        //'show' => 'admin.posts.create',
    ]);

    Route::get('/posts/{id}/download', 'PostsController@download')->name('posts.download');

});