// routes/api.php
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

Route::post('/compiler/execute', function (Request $request) {
    $response = Http::timeout(20)
        ->post(config('services.compiler.url') . '/execute', $request->only('language', 'code'));
    return response()->json($response->json(), $response->status());
});

Route::get('/compiler/health', function () {
    try {
        $response = Http::timeout(5)->get(config('services.compiler.url') . '/health');
        return response()->json($response->json(), $response->status());
    } catch (\Exception $e) {
        return response()->json(['status' => 'offline'], 503);
    }
});