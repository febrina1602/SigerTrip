<?php
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Illuminate\Foundation\Bootstrap\RegisterFacades;
use Illuminate\Foundation\Bootstrap\RegisterProviders;
use Illuminate\Foundation\Bootstrap\BootProviders;

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$users = \App\Models\User::where('role', 'agent')->get();
echo "Agent users found: " . $users->count() . "\n";
foreach ($users as $user) {
    echo "  ID: {$user->id}, Email: {$user->email}, Has Agent: " . ($user->agent ? "Yes" : "No") . "\n";
}
?>
