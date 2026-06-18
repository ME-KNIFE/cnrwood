<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\AdminUser;
use Illuminate\Support\Facades\Auth;

$provider = Auth::guard('admin')->getProvider();

$user = $provider->retrieveByCredentials([
    'email' => 'super@cnrwood.com',
]);

echo $user ? "PROVIDER_FOUND={$user->email}" . PHP_EOL : "PROVIDER_NOT_FOUND" . PHP_EOL;

if ($user) {
    $valid = $provider->validateCredentials($user, [
        'password' => 'Cnrwood2024!',
    ]);

    echo $valid ? "GUARD_PASSWORD_OK" . PHP_EOL : "GUARD_PASSWORD_BAD" . PHP_EOL;
    echo "ROLE={$user->role}" . PHP_EOL;
    echo "ACTIVE=" . ($user->is_active ? "1" : "0") . PHP_EOL;
}