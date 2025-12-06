<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Delete seeded agents with placeholder data
        DB::table('agents')
            ->where('name', 'PT Lampung Tour & Travel')
            ->orWhere('name', 'Sample Travel Co.')
            ->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nothing to restore
    }
};
