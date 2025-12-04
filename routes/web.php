<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Profile Module Routes
Route::resource('profiles', ProfileController::class);

// Lesson Module Routes
Route::resource('lessons', LessonController::class);

// Assignment Module Routes
Route::resource('assignments', AssignmentController::class);

// Discussion Module Routes
Route::resource('discussions', DiscussionController::class);
