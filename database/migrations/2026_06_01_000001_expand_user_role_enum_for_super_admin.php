<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('super_admin', 'admin', 'staff') NOT NULL DEFAULT 'staff'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'staff') NOT NULL DEFAULT 'staff'");
    }
};
