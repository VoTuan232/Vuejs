<?php

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/admin', 'admin\AdminController@index')->name('admin.index');
Route::get('/m/users', 'admin\UserController@index')->name('admin.user.index');
Route::get('/m/tags', 'admin\TagController@index')->name('admin.tag.index');
Route::get('/m/categories', 'admin\CategoryController@index')->name('admin.categories.index');
Route::get('/m/roles', 'admin\RoleController@index')->name('admin.roles.index');
Route::get('/m/posts', 'admin\PostController@index')->name('admin.posts.index');
Route::get('/m/comments', 'admin\CommentController@index')->name('admin.comments.index');
Route::get('/profile', 'admin\ProfileController@index')->name('user.profile');
