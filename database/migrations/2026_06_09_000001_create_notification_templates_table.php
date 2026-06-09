<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subject');
            $table->longText('body');
            $table->string('trigger_event');
            $table->string('recipient_type'); // roles, users, custom_emails
            $table->json('recipient_values')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('trigger_event');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_templates');
    }
};
