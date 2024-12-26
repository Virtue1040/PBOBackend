<?php

use Illuminate\Support\Facades\Route;

Route::get('/success', function () {
    return view('success');
})->name('success');
