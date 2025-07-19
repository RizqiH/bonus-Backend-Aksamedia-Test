<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NilaiController;

Route::get('/', function () {
    return view('welcome');
});

// Route untuk endpoint nilai RT dan ST
Route::get('/nilaiRT', [NilaiController::class, 'getNilaiRT']);
Route::get('/nilaiST', [NilaiController::class, 'getNilaiST']);
