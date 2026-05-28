<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeService
{
    /**
     * Generate a QR code as SVG for inline display.
     * The QR encodes a public URL pointing to the asset's public view page.
     */
    public function generateSVG(string $url, int $size = 200): string
    {
        return QrCode::format('svg')
            ->size($size)
            ->margin(1)
            ->errorCorrection('H')
            ->generate($url);
    }

    /**
     * Generate a QR code as base64-encoded SVG for <img src="">.
     * SVG is used instead of PNG since it does not require the imagick PHP extension.
     */
    public function generateBase64SVG(string $url, int $size = 200): string
    {
        return 'data:image/svg+xml;base64,' . base64_encode($this->generateSVG($url, $size));
    }

    /**
     * Generate a QR code as SVG string (replaces PNG which requires imagick).
     */
    public function generatePNG(string $url, int $size = 200): string
    {
        return $this->generateSVG($url, $size);
    }

    /**
     * Build the public-facing URL for a given UUID.
     */
    public function publicUrl(string $uuid): string
    {
        return route('assets.qr-public', ['uuid' => $uuid]);
    }
}
