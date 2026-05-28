<?php

namespace App\Services;

use Picqer\Barcode\BarcodeGeneratorSVG;
use Picqer\Barcode\BarcodeGeneratorPNG;

class BarcodeService
{
    /**
     * Generate a CODE128 barcode as an SVG string for inline HTML display.
     */
    public function generateSVG(string $code, int $widthFactor = 2, int $height = 60): string
    {
        $generator = new BarcodeGeneratorSVG();
        return $generator->getBarcode($code, $generator::TYPE_CODE_128, $widthFactor, $height);
    }

    /**
     * Generate a CODE128 barcode as a base64-encoded PNG suitable for <img src="">.
     */
    public function generateBase64PNG(string $code, int $widthFactor = 2, int $height = 60): string
    {
        $generator = new BarcodeGeneratorPNG();
        $png = $generator->getBarcode($code, $generator::TYPE_CODE_128, $widthFactor, $height);
        return 'data:image/png;base64,' . base64_encode($png);
    }

    /**
     * Generate a CODE128 barcode as raw PNG binary.
     */
    public function generatePNG(string $code, int $widthFactor = 2, int $height = 60): string
    {
        $generator = new BarcodeGeneratorPNG();
        return $generator->getBarcode($code, $generator::TYPE_CODE_128, $widthFactor, $height);
    }

    /**
     * Generate a unique barcode for an asset.
     * Format: BPA-YYYY-NNNNN
     */
    public function generateAssetBarcode(int $assetId, ?string $prefix = null): string
    {
        $prefix = $prefix ?? 'BPA';
        $year   = date('Y');
        return strtoupper($prefix) . '-' . $year . '-' . str_pad($assetId, 5, '0', STR_PAD_LEFT);
    }
}
