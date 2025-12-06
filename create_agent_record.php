<?php
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Create Agent record for user ID 4 if not exists
$user = \App\Models\User::find(4);
if ($user && !$user->agent) {
    $agent = \App\Models\Agent::create([
        'user_id' => $user->id,
        'name' => $user->full_name ?? 'Agent ' . $user->id,
        'agent_type' => 'LOCAL_TOUR',
        'contact_phone' => $user->phone_number ?? '',
        'is_verified' => false,
    ]);
    echo "✓ Created Agent record for user ID 4: " . $agent->id . "\n";
} else {
    echo "User ID 4 not found or already has Agent record\n";
}

// Verify
$user = \App\Models\User::find(4);
echo "User ID 4 now has Agent: " . ($user->agent ? "Yes (ID: " . $user->agent->id . ")" : "No") . "\n";
?>
