<?php

use Illuminate\Support\Facades\Route;

Route::get('admin', function () {
    return view('crud::admin.dashboard.index');
});