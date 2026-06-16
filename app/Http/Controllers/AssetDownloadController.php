<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AssetDownloadController extends Controller
{
    public function download(Asset $asset, $format)
    {
        if ($format === 'svg') {
            return Response::make($asset->svg_content, 200, [
                'Content-Type' => 'image/svg+xml',
                'Content-Disposition' => "attachment; filename=\"{$asset->title}.svg\"",
            ]);
        }

        if ($format === 'png') {
            if (!class_exists('Imagick')) {
                return redirect()->back()->with('error', 'The Imagick extension is not installed or enabled on this server. Please check your php.ini.');
            }

            try {
                $im = new \Imagick();
                $im->setBackgroundColor(new \ImagickPixel('transparent'));
                
                // Set resolution to 300 DPI before reading the SVG for high-quality PNGs
                $im->setResolution(300, 300);
                
                $im->readImageBlob($asset->svg_content);
                
                // Ensure the output is high-quality PNG
                $im->setImageFormat("png32");
                $im->stripImage(); // Remove unnecessary metadata
                
                return response($im->getImageBlob(), 200, [
                    'Content-Type' => 'image/png',
                    'Content-Disposition' => "attachment; filename=\"{$asset->title}.png\"",
                    'Cache-Control' => 'no-cache, no-store, must-revalidate',
                ]);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Failed to convert SVG to PNG: ' . $e->getMessage());
            }
        }
    }
}