<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\ScanLog;
use App\Services\QRCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QRCodeController extends Controller
{
    public function __construct(protected QRCodeService $qrService) {}

    /**
     * Regenerate QR UUID + SVG for an asset.
     */
    public function regenerate(Asset $asset)
    {
        $this->authorize('update', $asset);

        $asset->qr_uuid = Str::uuid()->toString();
        $asset->saveQuietly();

        return back()->with('success', 'QR Code regenerated. Old QR codes for this asset are now invalid.');
    }

    /**
     * Download QR code as SVG.
     */
    public function download(Asset $asset)
    {
        $this->authorize('view', $asset);

        $url = $this->qrService->publicUrl($asset->qr_uuid);
        $svg = $this->qrService->generateSVG($url, 300);

        return response($svg)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="qr-' . $asset->asset_tag . '.svg"');
    }

    /**
     * Inline SVG QR code for embedding.
     */
    public function image(Asset $asset)
    {
        $this->authorize('view', $asset);

        $url = $this->qrService->publicUrl($asset->qr_uuid);
        $svg = $this->qrService->generateSVG($url, 250);

        return response($svg)->header('Content-Type', 'image/svg+xml');
    }

    /**
     * Public page for a scanned QR code — no auth required.
     * Logs the scan.
     */
    public function publicView(Request $request, string $uuid)
    {
        $asset = Asset::where('qr_uuid', $uuid)->firstOrFail();

        // Log the scan
        ScanLog::create([
            'asset_id'   => $asset->id,
            'scanned_by' => auth()->id(),
            'scan_type'  => 'qr_code',
            'device'     => $request->header('User-Agent'),
            'ip_address' => $request->ip(),
            'location'   => null,
            'scanned_at' => now(),
        ]);

        return view('assets.qr-public', compact('asset'));
    }

    /**
     * Print QR label page.
     */
    public function print(Asset $asset)
    {
        $this->authorize('view', $asset);

        $url = $this->qrService->publicUrl($asset->qr_uuid);
        $svgQr = $this->qrService->generateSVG($url, 200);

        return view('labels.print', compact('asset', 'svgQr'));
    }
}
