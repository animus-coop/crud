<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('admin.dashboard'));
})->middleware('auth');


Route::name('admin.')->middleware('auth')->group(function() {
    Route::get('/dashboard', function () {
        return view('crud::admin.dashboard.index');
    })->name('dashboard');
});