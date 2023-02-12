<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\AdminController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'],function (){
    #Category
    Route::prefix('category')->name('category.')->group(function(){
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/{id}', [CategoryController::class, 'show'])->name('show');
        Route::post('/create', [CategoryController::class, 'store'])->name('store');
        Route::put('/update/{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CategoryController::class, 'delete'])->name('delete');
    });
});
