<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Root route - shows login page for guests, redirects authenticated users to dashboard
Route::get('/', [AuthController::class, 'showLogin'])->name('home');

// Clear session route - for testing/debugging
Route::get('/clear-session', function () {
    session()->flush();
    Auth::logout();
    return redirect('/')->with('message', 'Session cleared. Please login.');
})->name('clear-session');

// Authentication Routes - accessible to everyone, controller handles auth checks
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Password Reset Routes (Forgot Password)
Route::get('/forgot-password', [App\Http\Controllers\PasswordResetController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [App\Http\Controllers\PasswordResetController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\PasswordResetController::class, 'reset'])->name('password.reset.submit');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get');

// Session Keep-Alive Route (for maintaining active sessions during rapid navigation)
Route::get('/session/keep-alive', function () {
    // Touch the session to refresh last_activity
    session()->put('last_activity', now()->timestamp);
    session()->save();

    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
    ]);
})->middleware('auth')->name('session.keep-alive');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Module Routes
    Route::get('/profiles', [ProfileController::class, 'index'])->name('profiles.index');
    Route::get('/profiles/edit', [ProfileController::class, 'edit'])->name('profiles.edit');
    Route::put('/profiles', [ProfileController::class, 'update'])->name('profiles.update');
    Route::post('/profiles/photo', [ProfileController::class, 'updatePhoto'])->name('profiles.update-photo');
    Route::post('/profiles/photo/delete', [ProfileController::class, 'deletePhoto'])->name('profiles.delete-photo');
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
});
