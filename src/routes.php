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
    Route::get('/', 'App\Http\Controllers\admin\UserController@index')
         ->name('user.index');
    Route::get('/create', 'App\Http\Controllers\admin\UserController@create')
         ->name('user.create');
    Route::get('/show/{user}', 'App\Http\Controllers\admin\UserController@show')
         ->name('user.show')->where('id', '[0-9]+');
    Route::get('/{user}/edit', 'App\Http\Controllers\admin\UserController@edit')
         ->name('user.edit')->where('id', '[0-9]+');
    Route::post('/', 'App\Http\Controllers\admin\UserController@store')
         ->name('user.store');
    Route::put('user/{user}', 'App\Http\Controllers\admin\UserController@update')
         ->name('user.update')->where('id', '[0-9]+');
    Route::delete('/user/{user}', 'App\Http\Controllers\admin\UserController@destroy')
         ->name('user.destroy')->where('id', '[0-9]+');
});