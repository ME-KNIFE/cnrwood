<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            ProductCategorySeeder::class,
            TestProductSeeder::class,
            SiteSettingsSeeder::class,
            SitePageSeeder::class,
        ]);
    }
}
