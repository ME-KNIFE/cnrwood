<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;

$u = AdminUser::updateOrCreate(
    ['email' => 'super@cnrwood.com'],
    [
        'name' => 'Super Admin',
        'role' => 'super_admin',
        'password' => Hash::make('Cnrwood2024!'),
        'is_active' => true,
    ]
);

echo "SUPER ADMIN RESET OK" . PHP_EOL;
echo $u->email . " | " . $u->role . " | active=" . ($u->is_active ? "1" : "0") . PHP_EOL;