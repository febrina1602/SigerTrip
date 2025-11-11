<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            if (!Schema::hasColumn('destinations', 'rating')) {
                // numeric(2,1) cukup untuk 0.0 - 9.9. Kalau mau 0.0 - 5.0 juga aman.
                $table->decimal('rating', 2, 1)->default(0)->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            if (Schema::hasColumn('destinations', 'rating')) {
                $table->dropColumn('rating');
            }
        });
    }
};
