<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY role VARCHAR(255) NOT NULL DEFAULT 'staff'");

        DB::statement(
            "INSERT IGNORE INTO role_user (role_id, user_id)
            SELECT roles.id, users.id
            FROM users
            INNER JOIN roles ON roles.slug = users.role"
        );
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('super_admin', 'admin', 'staff') NOT NULL DEFAULT 'staff'");
    }
};
