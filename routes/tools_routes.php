<?php

use App\Http\Controllers\ToolsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tools Routes — add to your existing web.php
|--------------------------------------------------------------------------
*/

Route::prefix('tools')->name('tools.')->group(function () {
    Route::get('/',          [ToolsController::class, 'index'])     ->name('index');
    Route::get('/pdf',       [ToolsController::class, 'pdf'])       ->name('pdf');
    Route::get('/document',  [ToolsController::class, 'document'])  ->name('document');
    Route::get('/data',      [ToolsController::class, 'data'])      ->name('data');
    Route::get('/image',     [ToolsController::class, 'image'])     ->name('image');
    Route::get('/developer', [ToolsController::class, 'developer']) ->name('developer');
});