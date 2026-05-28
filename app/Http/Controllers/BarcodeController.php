<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Services\BarcodeService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BarcodeController extends Controller
{
    public function __construct(protected BarcodeService $barcodeService) {}

    /**
     * Show the printable barcode label for an asset.
     */
    public function show(Asset $asset)
    {
        $this->authorize('view', $asset);

        $svg = $this->barcodeService->generateSVG($asset->barcode ?? 'NO-CODE', 2, 70);
        return view('labels.barcode', compact('asset', 'svg'));
    }

    /**
     * Download barcode as PNG image.
     */
    public function download(Asset $asset)
    {
        $this->authorize('view', $asset);

        $png = $this->barcodeService->generatePNG($asset->barcode ?? 'NO-CODE', 2, 80);

        return response($png)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="barcode-' . $asset->barcode . '.png"');
    }

    /**
     * Inline SVG barcode for embedding in views.
     */
    public function svg(Asset $asset)
    {
        $this->authorize('view', $asset);

        $svg = $this->barcodeService->generateSVG($asset->barcode ?? 'NO-CODE', 2, 60);

        return response($svg)->header('Content-Type', 'image/svg+xml');
    }

    /**
     * Regenerate barcode for an asset (admin only).
     */
    public function regenerate(Request $request, Asset $asset)
    {
        $this->authorize('update', $asset);

        $custom = $request->input('barcode');

        if ($custom) {
            // Check uniqueness
            $exists = Asset::where('barcode', $custom)->where('id', '!=', $asset->id)->exists();
            if ($exists) {
                return back()->withErrors(['barcode' => 'Barcode already in use by another asset.']);
            }
            $asset->barcode = $custom;
        } else {
            $asset->barcode = $this->barcodeService->generateAssetBarcode($asset->id);
        }

        $asset->saveQuietly();

        return back()->with('success', 'Barcode updated successfully.');
    }

    /**
     * Print barcode label page.
     */
    public function print(Asset $asset)
    {
        $this->authorize('view', $asset);

        $svg = $this->barcodeService->generateSVG($asset->barcode ?? 'NO-CODE', 2, 70);
        return view('labels.print', compact('asset', 'svg'));
    }
}
