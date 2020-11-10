<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('crud::admin.dashboard.index');
})->name('admin.dashboard')->middleware('auth');

Route::name('admin.')->prefix('admin')->middleware('auth')->group(function() {
    Route::get('users/roles', 'UserController@roles')->name('users.roles');
    Route::resource('users', 'UserController', [
        'names' => [
            'index' => 'users'
        ]
    ]);
});

Route::name('js.')->group(function() {
    Route::get('dynamic.js', 'JsController@dynamic')->name('dynamic');
});