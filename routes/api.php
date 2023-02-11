<?php

use App\Http\Controllers\Api\AdminController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest'],function (){
    Route::post('/register', [AdminController::class, 'register'])->name('register');
    Route::post('/login', [AdminController::class, 'login'])->name('login');
});
