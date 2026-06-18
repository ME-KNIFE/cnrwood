<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'      => 'Super Admin',
                'email'     => 'super@cnrwood.com',
                'role'      => 'super_admin',
                'password'  => Hash::make('Cnrwood2024!'),
            ],
            [
                'name'      => 'Satış Müdürü',
                'email'     => 'satis@cnrwood.com',
                'role'      => 'sales_manager',
                'password'  => Hash::make('Cnrwood2024!'),
            ],
            [
                'name'      => 'İçerik Editörü',
                'email'     => 'editor@cnrwood.com',
                'role'      => 'editor',
                'password'  => Hash::make('Cnrwood2024!'),
            ],
            [
                'name'      => 'Ürün Yöneticisi',
                'email'     => 'urun@cnrwood.com',
                'role'      => 'product_manager',
                'password'  => Hash::make('Cnrwood2024!'),
            ],
            [
                'name'      => 'Destek Ekibi',
                'email'     => 'destek@cnrwood.com',
                'role'      => 'support',
                'password'  => Hash::make('Cnrwood2024!'),
            ],
            [
                'name'      => 'Mağaza Sorumlusu',
                'email'     => 'magaza@cnrwood.com',
                'role'      => 'store_manager',
                'password'  => Hash::make('Cnrwood2024!'),
            ],
        ];

        foreach ($users as $userData) {
            AdminUser::updateOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, ['is_active' => true])
            );
        }

        $this->command->info('AdminUser seeder: 6 users created (all roles).');
        $this->command->warn('⚠️  Change all passwords immediately after first login!');
    }
}
