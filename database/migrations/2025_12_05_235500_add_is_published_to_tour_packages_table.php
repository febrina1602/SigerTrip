<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tour_packages', function (Blueprint $table) {
            if (!Schema::hasColumn('tour_packages', 'is_published')) {
                $table->boolean('is_published')->default(true)->after('cover_image_url');
                $table->index('is_published');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tour_packages', function (Blueprint $table) {
            if (Schema::hasColumn('tour_packages', 'is_published')) {
                $table->dropIndex(['is_published']);
                $table->dropColumn('is_published');
            }
        });
    }
};
