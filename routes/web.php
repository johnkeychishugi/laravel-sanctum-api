<?php

use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/api-docs', function () {
    return view('swagger.index');
})->name('docs');
