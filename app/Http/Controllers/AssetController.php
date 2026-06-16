<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetCategory;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    /**
     * Display a listing of the assets.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categorySlug = $request->input('category');

        $categories = AssetCategory::all();

        $assets = Asset::query()
            ->when($categorySlug, function ($query, $categorySlug) {
                $query->whereHas('category', function ($q) use ($categorySlug) {
                    $q->where('slug', $categorySlug);
                });
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('keywords', 'like', "%{$search}%");
                });
            })
            ->with('category')
            ->latest()
            ->paginate(24);

        return view('assets.index', compact('assets', 'categories'));
    }
}