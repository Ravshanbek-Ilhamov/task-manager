<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\ControllController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TaskAreaController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserTaskController;

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
Route::post('/tasks/filter', [TaskController::class, 'filterDate'])->name('alltasks.filter');
Route::get('/alltasks/filter/{status}', [TaskController::class, 'takeFilterTask'])->name('user-tasks.filter');

Route::get('filter-tasks/{filter}',[TaskController::class,'filter']);

Route::get('/responses', [TaskController::class, 'response_page'])->name('response.index');
Route::post('/tasks/{taskAreaStatus}/open', [TaskController::class, 'openTask'])->name('tasks.open');
Route::post('/tasks/{taskAreaStatus}/do', [TaskController::class, 'doTask'])->name('tasks.do');
Route::patch('/responses/{id}/accept', [TaskController::class, 'accept'])->name('responses.accept');
Route::patch('/responses/{id}/reject', [TaskController::class, 'reject'])->name('responses.reject');
Route::get('/responses', [TaskController::class, 'response_page'])->name('responses.page');
Route::patch('/responses/reject-with-comment', [TaskController::class, 'rejectWithComment'])->name('responses.rejectWithComment');


Route::get('/user-tasks',[UserTaskController::class,'index']);
Route::get('/user-tasks/filter', [UserTaskController::class, 'filterDate'])->name('user.tasks.filter');
Route::get('/user-tasks/filter/{status}', [UserTaskController::class, 'takeFilterTask'])->name('user-tasks.filter');


Route::get('/reset-user',[AuthController::class,'reset_page'])->name('reset.page');
Route::put('/users/{id}', [AuthController::class, 'user_update'])->name('users.update');


Route::get('/controll',[ControllController::class,'index'])->name('control.index');
Route::get('/tasklar/{area}/{category}', [ControllController::class, 'showTasksByAreaAndCategory'])->name('tasks.byAreaAndCategory');
Route::get('/filter/{status}', [ControllController::class, 'filterByStatus'])->name('controltasks.filter');

Route::get('/reports',[ReportController::class,'index'])->name('report.index');

