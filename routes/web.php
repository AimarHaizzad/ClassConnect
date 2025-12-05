<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\CommentController;
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
Route::get('/password/change', [ProfileController::class, 'showChangePassword'])->name('password.change');
Route::put('/password/update', [ProfileController::class, 'updatePassword'])->name('password.update');

// Lesson Module Routes
Route::resource('lessons', LessonController::class);

// Assignment Module Routes
Route::resource('assignments', AssignmentController::class);

// Subject Selection Routes
Route::get('/subjects/select', [App\Http\Controllers\SubjectController::class, 'select'])->name('subjects.select');
Route::post('/subjects/select', [App\Http\Controllers\SubjectController::class, 'store'])->name('subjects.store');
Route::post('/subjects/clear', [App\Http\Controllers\SubjectController::class, 'clear'])->name('subjects.clear');

// Discussion Module Routes (requires subject selection)
Route::get('/discussions', function () {
    if (! session('selected_subject_id')) {
        return redirect()->route('subjects.select');
    }

    return app(DiscussionController::class)->index();
})->name('discussions.index');

Route::post('/discussions', [DiscussionController::class, 'store'])
    ->middleware('throttle:5,1') // 5 discussions per minute
    ->name('discussions.store');

Route::resource('discussions', DiscussionController::class)->except(['index', 'store']);

// Comment Routes with rate limiting
Route::post('comments', [CommentController::class, 'store'])
    ->middleware('throttle:10,1') // 10 comments per minute
    ->name('comments.store');
