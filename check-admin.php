<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;

$u = AdminUser::where('email', 'super@cnrwood.com')->first();

if (! $u) {
    echo "NOT FOUND" . PHP_EOL;
    exit;
}

echo "FOUND: {$u->email} | role={$u->role} | active=" . ($u->is_active ? "1" : "0") . PHP_EOL;
echo Hash::check('Cnrwood2024!', $u->password) ? "PASSWORD_OK" . PHP_EOL : "PASSWORD_BAD" . PHP_EOL;

if (method_exists($u, 'canAccessAdminPanel')) {
    echo "CAN_ADMIN_PANEL=" . ($u->canAccessAdminPanel() ? "true" : "false") . PHP_EOL;
}