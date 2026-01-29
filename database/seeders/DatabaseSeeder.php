<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Artisan::call('optimize:clear', [
            '--no-interaction' => true,
            '--quiet' => app()->isProduction(),
        ]);

        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            SidebarMenuSeeder::class,
            RegionSeeder::class,
            UserTypeSeeder::class,
            UserSeeder::class,
            UserDetailSeeder::class,
            UserApiSeeder::class,
            IrrigationSettingSeeder::class,
            BlockSeeder::class,
            CoordinateSeeder::class,
            SprayerSeeder::class,
            SensorSeeder::class,
        ]);
    }
}
