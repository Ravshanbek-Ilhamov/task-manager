<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\TaskAreaController;
use App\Http\Controllers\TaskController;

// Route::get('/', function () {
//     return view('welcome');
// });



Route::get('/',[AuthController::class,'login_page'])->name('login.index');
Route::post('/login',[AuthController::class,'login'])->name('login');

Route::get('/registeration',[AuthController::class,'register_page'])->name('register.index');
Route::post('/register',[AuthController::class,'register'])->name('register');


Route::get('/users', [UserController::class, 'index'])->name('user.index');
Route::post('/users', [UserController::class, 'store'])->name('user.store');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
Route::put('/users/{id}', [UserController::class, 'update'])->name('user.update');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('user.destroy');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');


Route::get('/categories', [CategoryController::class, 'index'])->name('category.index');
Route::post('/category/store', [CategoryController::class, 'store']);
Route::put('/category/update', [CategoryController::class, 'update']);
Route::delete('/category/delete/{category}', [CategoryController::class, 'destroy']);


Route::get('/areas', [AreaController::class, 'index']);
Route::post('/area/store', [AreaController::class, 'store']);
Route::put('/area/update', [AreaController::class, 'update']);
Route::delete('/area/delete/{id}', [AreaController::class, 'destroy']);

Route::resource('tasks', TaskController::class);
Route::resource('taskAreas', TaskAreaController::class);
