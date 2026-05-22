<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('employee_id')->nullable()->after('password');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null')->after('employee_id');
            $table->string('phone')->nullable()->after('department_id');
            $table->string('position')->nullable()->after('phone');
            $table->enum('role', ['admin', 'staff'])->default('staff')->after('position');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['employee_id', 'department_id', 'phone', 'position', 'role']);
            $table->dropSoftDeletes();
        });
    }
};
