<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ResumeController extends Controller
{
    public function index()
    {
        return view('resume.index');
    }

    public function analyze(Request $request)
    {
        $request->validate([
            'resume' => 'required|file|mimes:pdf,docx,txt|max:5120',
            'job_description' => 'required|string|min:50',
        ]);

        try {
            $response = Http::attach(
                    'resume',
                    fopen($request->file('resume')->getRealPath(), 'r'),
                    $request->file('resume')->getClientOriginalName()
                )
                ->timeout(config('services.resume_analyzer.timeout', 30))
                ->post(config('services.resume_analyzer.url') . '/analyze', [
                    'job_description' => $request->job_description,
            ]);

            if ($response->failed()) {
                return back()->withErrors(['api' => 'Analysis failed: ' . ($response->json()['detail'] ?? 'Unknown error')]);
            }

            return view('resume.results', ['data' => $response->json()]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()->withErrors(['api' => 'Could not connect to Analysis Engine.']);
        }
    }
}