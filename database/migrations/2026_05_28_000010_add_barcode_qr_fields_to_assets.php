<?php

use App\Services\QRCodeService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->string('barcode')->nullable()->unique()->after('asset_tag');
            $table->string('qr_uuid')->nullable()->unique()->after('barcode');
            $table->text('qr_code')->nullable()->after('qr_uuid');
        });

        // Backfill existing assets with barcode, UUID, and QR code
        $qrService = new QRCodeService();
        \App\Models\Asset::withTrashed()->get()->each(function ($asset) use ($qrService) {
            $updates = [];

            if (empty($asset->barcode)) {
                $updates['barcode'] = 'BPA-' . date('Y') . '-' . str_pad($asset->id, 5, '0', STR_PAD_LEFT);
            }

            if (empty($asset->qr_uuid)) {
                $updates['qr_uuid'] = Str::uuid()->toString();
            }

            if (empty($asset->qr_code)) {
                $uuid = $updates['qr_uuid'] ?? $asset->qr_uuid;
                if ($uuid) {
                    $updates['qr_code'] = $qrService->generateSVG($qrService->publicUrl($uuid), 200);
                }
            }

            if (!empty($updates)) {
                $asset->timestamps = false;
                $asset->update($updates);
            }
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['barcode', 'qr_uuid', 'qr_code']);
        });
    }
};
