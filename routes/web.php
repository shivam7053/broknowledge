<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\BlogController;
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
