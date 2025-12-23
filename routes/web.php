<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::post('/lessons/store', [LessonController::class, 'store'])->name('lessons.store');
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

// API route for checking field uniqueness (for inline validation)
Route::get('/api/check-unique', function (Request $request) {
    $field = $request->query('field');
    $value = $request->query('value');

    if (! in_array($field, ['username', 'email', 'user_id'])) {
        return response()->json(['error' => 'Invalid field'], 400);
    }

    $exists = \App\Models\User::where($field, $value)->exists();

    $fieldLabel = str_replace('_', ' ', $field);

    return response()->json([
        'unique' => ! $exists,
        'message' => $exists ? "This {$fieldLabel} is already taken." : null,
    ]);
})->name('api.check-unique');

// Password Reset Routes (Forgot Password)
Route::get('/forgot-password', [App\Http\Controllers\PasswordResetController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [App\Http\Controllers\PasswordResetController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\PasswordResetController::class, 'reset'])->name('password.reset.submit');
Route::get('/lessons', [LessonController::class, 'index'])->name('lessons.index');
Route::get('/lessons/lessonCreate', [LessonController::class, 'lessonCreate'])->name('lessons.lessonCreate');
Route::resource('lessons', LessonController::class);
Route::get('/lessons/file/{id}', [LessonController::class, 'file'])->name('lessons.file');
Route::delete('/lessons/files/{id}', [LessonController::class, 'deleteFile'])->name('lessons.deleteFile');

Route::post('/lessons/store', [LessonController::class, 'store'])->name('lessons.store');
Route::get('/lessons/lessonForm', [LessonController::class, 'lessonForm'])->name('lessons.lessonForm');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get');

// Session Keep-Alive Route (for maintaining active sessions during rapid navigation)
Route::get('/session/keep-alive', function () {
    // Check if user is authenticated
    if (! auth()->check()) {
        return response()->json([
            'status' => 'expired',
            'message' => 'Session expired',
            'timestamp' => now()->toIso8601String(),
        ], 200); // Return 200 to avoid showing as error in browser
    }

    // Touch the session to refresh last_activity
    session()->put('last_activity', now()->timestamp);
    session()->save();

    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
    ]);
})->name('session.keep-alive');

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
        ->middleware('throttle:20,1') // 20 discussions per minute (increased for testing)
        ->name('discussions.store');

Route::resource('discussions', DiscussionController::class)->except(['index', 'store']);



    // Comment Routes with rate limiting
    Route::post('comments', [CommentController::class, 'store'])
        ->middleware('throttle:10,1') // 10 comments per minute
        ->name('comments.store');
    Route::get('comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});
