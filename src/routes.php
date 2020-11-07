<?php

use Illuminate\Support\Facades\Route;

Route::mixin(new \Laravel\Ui\AuthRouteMethods());
Route::auth(['verify' => true]);

Route::get('admin', function () {
    return view('crud::admin.dashboard.index');
});

Route::name('admin.')->prefix('admin')->middleware('auth')->group(function() {
    Route::get('users/roles', 'UserController@roles')->name('users.roles');
    Route::resource('users', 'UserController', [
        'names' => [
            'index' => 'users'
        ]
    ]);
});

//Route::middleware('auth')->get('logout', function() {
//    Auth::logout();
//    return redirect(route('login'))->withInfo('You have successfully logged out!');
//})->name('logout');

Auth::routes(['verify' => true]);

Route::name('js.')->group(function() {
    Route::get('dynamic.js', 'JsController@dynamic')->name('dynamic');
});

// Get authenticated user
Route::get('users/auth', function() {
    return response()->json(['user' => Auth::check() ? Auth::user() : false]);
});
