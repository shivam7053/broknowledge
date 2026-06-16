<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Compiler Pages — one route per language
|--------------------------------------------------------------------------
| Add these to your routes/web.php via:
|   require __DIR__.'/compiler_web.php';
|
| Or paste the routes directly into routes/web.php
|--------------------------------------------------------------------------
*/

// ── Compiler Pages ────────────────────────────────────────────────────────
Route::get('/compilers',          fn() => redirect()->route('compiler.html'))->name('compilers');
Route::get('/compilers/html',     fn() => view('compilers.html'))->name('compiler.html');
Route::get('/compilers/python',   fn() => view('compilers.python'))->name('compiler.python');
Route::get('/compilers/cpp',      fn() => view('compilers.cpp'))->name('compiler.cpp');
Route::get('/compilers/java',     fn() => view('compilers.java'))->name('compiler.java');
Route::get('/compilers/php',      fn() => view('compilers.php-lang'))->name('compiler.php');

// ── Proxy Routes (no CSRF, same-origin so no mixed-content) ───────────────
// These sit in web.php but are excluded from CSRF via bootstrap/app.php
Route::post('/compilers/run',    function (Request $request) {
    $validated = $request->validate([
        'language' => 'required|string|in:python,cpp,java,php',
        'code'     => 'required|string|max:65000',
    ]);

    $baseUrl = rtrim(config('services.compiler.url'), '/');

    try {
        $response = Http::timeout(20)
            ->post("{$baseUrl}/execute", $validated);

        return response()->json($response->json(), $response->status());
    } catch (\Illuminate\Http\Client\ConnectionException $e) {
        return response()->json([
            'output'    => '⚠ Compiler API unreachable. Is it running on Render?',
            'exit_code' => -1,
            'timed_out' => false,
        ], 503);
    }
})->name('compiler.run');

Route::get('/compilers/health', function () {
    $baseUrl = rtrim(config('services.compiler.url'), '/');
    try {
        $response = Http::timeout(5)->get("{$baseUrl}/health");
        return response()->json($response->json(), $response->status());
    } catch (\Exception) {
        return response()->json(['status' => 'offline'], 503);
    }
})->name('compiler.health');