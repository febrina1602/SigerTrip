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
        // Backfill agent_id in tour_packages from local_tour_agents.agent_id
        // This is done in PHP to remain DB-agnostic.
        $packages = DB::table('tour_packages')
            ->whereNull('agent_id')
            ->whereNotNull('local_tour_agent_id')
            ->get();

        foreach ($packages as $pkg) {
            $local = DB::table('local_tour_agents')->where('id', $pkg->local_tour_agent_id)->first();
            if ($local && isset($local->agent_id)) {
                DB::table('tour_packages')->where('id', $pkg->id)->update(['agent_id' => $local->agent_id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No destructive rollback: we won't nullify agent_id automatically.
    }
};
