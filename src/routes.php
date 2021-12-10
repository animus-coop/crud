<?php

use Illuminate\Support\Facades\Route;

// Dashboard
Route::get('/', function () {
    return redirect(route('admin.dashboard'));
});

Route::middleware(['auth:sanctum', 'verified', 'role:admin|professional'])->get('/dashboard', 'App\Http\Controllers\admin\DashboardController@index')
     ->name('admin.dashboard');

// Users - Admin Auth
Route::group([
    'prefix' => 'users',
    'middleware' => ['auth', 'role:admin']
], function () {
    Route::get('/', 'App\Http\Controllers\Admin\UserController@index')
         ->name('user.index');
    Route::get('/create', 'App\Http\Controllers\Admin\UserController@create')
         ->name('user.create');
    Route::get('/show/{user}', 'App\Http\Controllers\Admin\UserController@show')
         ->name('user.show')->where('id', '[0-9]+');
    Route::get('/{user}/edit', 'App\Http\Controllers\Admin\UserController@edit')
         ->name('user.edit')->where('id', '[0-9]+');
    Route::post('/', 'App\Http\Controllers\Admin\UserController@store')
         ->name('user.store');
    Route::put('user/{user}', 'App\Http\Controllers\Admin\UserController@update')
         ->name('user.update')->where('id', '[0-9]+');
    Route::delete('/user/{user}', 'App\Http\Controllers\Admin\UserController@destroy')
         ->name('user.destroy')->where('id', '[0-9]+');
});
