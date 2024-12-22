<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'role_or_permission:admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('category', CategoryController::class);
});

Route::middleware(['auth', 'verified', 'role_or_permission:user|admin'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('task', TaskController::class);

    Route::get('/task-schedule', [TaskController::class, 'schedule'])->name('task.schedule');
    Route::get('/task-history', [TaskController::class, 'history'])->name('task.history');
    Route::get('/task-all', [TaskController::class, 'all'])->name('task.all');
    Route::patch('/task-complete/{id}', [TaskController::class, 'task_complete'])->name('task.complete');
    Route::patch('/task-cancel/{id}', [TaskController::class, 'task_cancel'])->name('task.cancel');
});


require __DIR__ . '/auth.php';
