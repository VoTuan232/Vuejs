<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('m/users', 'api\admin\UserController@index');
Route::post('m/user', 'api\admin\UserController@store');
Route::delete('m/user/{user}', 'api\admin\UserController@destroy');
Route::put('m/user/{user}', 'api\admin\UserController@update');

Route::get('m/roles/all', 'api\admin\RoleController@getAllRole');

Route::get('profile', 'api\admin\UserController@profile');
Route::put('profile', 'api\admin\UserController@updateProfile');

Route::get('m/tags', 'api\admin\TagController@index');
Route::post('m/tag', 'api\admin\TagController@store');
Route::put('m/tag/{tag}', 'api\admin\TagController@update');
Route::delete('m/tag/{tag}', 'api\admin\TagController@destroy');


Route::get('m/categories', 'api\admin\CategoryController@index');
Route::get('m/categories_model', 'api\admin\CategoryController@getCategoryModel');
Route::post('m/category', 'api\admin\CategoryController@store');
Route::put('m/category/{category}', 'api\admin\CategoryController@update');
Route::delete('m/category/{category}', 'api\admin\CategoryController@destroy');

Route::get('m/roles', 'api\admin\RoleController@index');
Route::post('m/role', 'api\admin\RoleController@store');
Route::put('m/role/{role}', 'api\admin\RoleController@update');
Route::delete('m/role/{role}', 'api\admin\RoleController@destroy');

