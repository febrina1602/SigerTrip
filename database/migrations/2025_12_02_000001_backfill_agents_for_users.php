<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create agent records for existing users who have role 'agent' but no agent record.
        $users = DB::table('users')
            ->where('role', 'agent')
            ->get();

        foreach ($users as $user) {
            $exists = DB::table('agents')->where('user_id', $user->id)->exists();
            if (!$exists) {
                DB::table('agents')->insert([
                    'user_id' => $user->id,
                    'name' => $user->full_name ?? $user->email ?? 'Agent',
                    'agent_type' => 'LOCAL_TOUR',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No automatic rollback to avoid accidental removals.
    }
};
