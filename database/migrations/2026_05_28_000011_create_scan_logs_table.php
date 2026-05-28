<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scan_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade');
            $table->foreignId('scanned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('scan_type')->default('barcode'); // barcode, qr_code, serial_number
            $table->string('device')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('location')->nullable();
            $table->timestamp('scanned_at')->useCurrent();
            $table->timestamps();

            $table->index(['asset_id', 'scanned_at']);
            $table->index('scanned_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scan_logs');
    }
};
