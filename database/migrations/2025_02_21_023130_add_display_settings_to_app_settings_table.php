<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->json('completed_steps')->nullable();
            $table->string('post_install_redirect')->default('admin');
            $table->string('display_mode')->default('embedded');
            $table->string('admin_redirect_url')->nullable();
            $table->boolean('enable_frontend')->default(false);
            $table->string('frontend_url')->nullable();
            $table->boolean('is_installed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};