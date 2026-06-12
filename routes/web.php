<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ToolsController;
use App\Models\Course;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home', [
        'courses' => Course::all(),
        'posts' => Post::with('category')->latest()->get()
    ]);
})->name('home');

Route::get('/courses', function () {
    return view('welcome', ['courses' => Course::all()]);
})->name('courses.index');

Route::get('/courses/{course:slug}/{topic:slug?}', [CourseController::class, 'show'])->name('courses.show');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

Route::prefix('tools')->name('tools.')->group(function () {
    Route::get('/',          [ToolsController::class, 'index'])     ->name('index');
    Route::get('/pdf',       [ToolsController::class, 'pdf'])       ->name('pdf');
    Route::get('/document',  [ToolsController::class, 'document'])  ->name('document');
    Route::get('/data',      [ToolsController::class, 'data'])      ->name('data');
    Route::get('/image',     [ToolsController::class, 'image'])     ->name('image');
    Route::get('/developer', [ToolsController::class, 'developer']) ->name('developer');
});

Route::view('/compilers', 'compilers')->name('compilers');

Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/terms', 'terms')->name('terms');


Route::view('/games', 'games')->name('games');
Route::view('/games/card', 'games.card')->name('games.card');
Route::view('/games/snake', 'games.snake')->name('games.snake');
Route::view('/games/tetris', 'games.tetris')->name('games.tetris');
Route::view('/games/shooter', 'games.shooter')->name('games.shooter');
Route::view('/games/bomber', 'games.bomber')->name('games.bomber');

// Quiz Routes
Route::prefix('quizzes')->name('quizzes.')->group(function () {
    Route::get('/', [QuizController::class, 'index'])->name('index');
    Route::get('/{quizCategory:slug}', [QuizController::class, 'showCategoryQuizzes'])->name('showCategoryQuizzes');
    Route::get('/take/{quiz:slug}', [QuizController::class, 'takeQuiz'])->name('take');
    // Route for submitting quiz results (will be implemented later)
    Route::post('/submit/{quiz:slug}', [QuizController::class, 'submitQuiz'])->name('submit');
});

// Free API Routes
Route::get('/free-apis', function () {
    // For now, returning a static view or an empty collection if model isn't ready
    return view('apis.index', ['apis' => \App\Models\FreeApi::where('is_active', true)->latest()->get() ?? collect()]);
})->name('apis.index');
