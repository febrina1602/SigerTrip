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
            // Tambah kolom foreign key untuk local_tour_agent_id
            $table->foreignId('local_tour_agent_id')
                  ->nullable()
                  ->constrained('local_tour_agents')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tour_packages', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\LocalTourAgent::class);
        });
    }
};
