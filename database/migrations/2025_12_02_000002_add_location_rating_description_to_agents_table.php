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
        Schema::table('agents', function (Blueprint $table) {
            if (!Schema::hasColumn('agents', 'location')) {
                $table->string('location')->nullable()->after('address');
            }
            if (!Schema::hasColumn('agents', 'rating')) {
                $table->decimal('rating', 3, 2)->default(0)->after('contact_phone');
            }
            if (!Schema::hasColumn('agents', 'description')) {
                $table->text('description')->nullable()->after('banner_image_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            if (Schema::hasColumn('agents', 'location')) {
                $table->dropColumn('location');
            }
            if (Schema::hasColumn('agents', 'rating')) {
                $table->dropColumn('rating');
            }
            if (Schema::hasColumn('agents', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
