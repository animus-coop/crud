<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('admin.dashboard'));
})->middleware('auth');


Route::name('admin.')->middleware('auth')->group(function() {
    Route::get('users/roles', 'UserController@roles')->name('users.roles');
    Route::resource('users', 'UserController', [
        'names' => [
            'index' => 'users'
        ]
    ]);
    Route::get('/dashboard', function () {
        return view('crud::admin.dashboard.index');
    })->name('dashboard');
});

Route::name('js.')->group(function() {
    Route::get('dynamic.js', 'JsController@dynamic')->name('dynamic');
});