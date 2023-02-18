<?php

use App\Http\Controllers\Api\UserCourseController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ArtisanController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest'], function () {
    Route::post('/register', [AdminController::class, 'register'])->name('register');
    Route::post('/login', [AdminController::class, 'login'])->name('login');
});

Route::group(['middleware' => 'auth'], function () {
    #Category
    Route::prefix('category')->name('category.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/{id}', [CategoryController::class, 'show'])->name('show');
        Route::post('/create', [CategoryController::class, 'store'])->name('store');
        Route::put('/update/{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CategoryController::class, 'delete'])->name('delete');
    });

    #Course
    Route::prefix('course')->name('course.')->group(function () {
        Route::get('/', [CourseController::class, 'index'])->name('index');
        Route::get('/{id}', [CourseController::class, 'show'])->name('show');
        Route::post('/create', [CourseController::class, 'store'])->name('store');
        Route::put('/update/{id}', [CourseController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CourseController::class, 'delete'])->name('delete');
    });

    #User
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::post('/create', [UserController::class, 'store'])->name('store');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [UserController::class, 'delete'])->name('delete');
    });

    #UserCourse
    Route::prefix('usercourse')->name('usercourse.')->group(function () {
        Route::get('/', [UserCourseController::class, 'index'])->name('index');
        Route::get('/user/{id}', [UserCourseController::class, 'showByUser'])->name('show.user');
        Route::get('/course/{id}', [UserCourseController::class, 'showByCourse'])->name('show.course');
        Route::post('/create', [UserCourseController::class, 'store'])->name('store');
        Route::put('/update/{id}', [UserCourseController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [UserCourseController::class, 'delete'])->name('delete');
    });
});

#ArtisanCall
Route::prefix('artisan')->group(function () {
    Route::get('/fresh', [ArtisanController::class, 'fresh']);
    Route::get('/optimize', [ArtisanController::class, 'optimize']);
});
