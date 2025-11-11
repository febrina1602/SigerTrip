<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            if (!Schema::hasColumn('destinations', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->index();
            }
            // Opsional:
            // if (!Schema::hasColumn('destinations', 'rating')) {
            //     $table->decimal('rating', 2, 1)->default(0)->index();
            // }
        });
    }

    public function down(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            if (Schema::hasColumn('destinations', 'is_featured')) {
                $table->dropColumn('is_featured');
            }
            // if (Schema::hasColumn('destinations', 'rating')) {
            //     $table->dropColumn('rating');
            // }
        });
    }
};
