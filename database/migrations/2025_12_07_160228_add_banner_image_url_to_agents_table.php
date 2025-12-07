<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            if (!Schema::hasColumn('agents', 'banner_image_url')) {
                $table->string('banner_image_url')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            if (Schema::hasColumn('agents', 'banner_image_url')) {
                $table->dropColumn('banner_image_url');
            }
        });
    }
};