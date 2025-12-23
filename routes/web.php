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

// Lesson Module Routes
Route::get('/lessons', [LessonController::class, 'index'])->name('lessons.index');
Route::get('/lessons/lessonCreate', [LessonController::class, 'lessonCreate'])->name('lessons.lessonCreate');
Route::resource('lessons', LessonController::class);
Route::get('/lessons/file/{id}', [LessonController::class, 'file'])->name('lessons.file');
Route::delete('/lessons/files/{id}', [LessonController::class, 'deleteFile'])->name('lessons.deleteFile');

Route::post('/lessons/store', [LessonController::class, 'store'])->name('lessons.store');
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
