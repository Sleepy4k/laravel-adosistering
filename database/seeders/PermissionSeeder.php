<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Permission::query()->count() > 0) return;

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = Permission::factory()->make();

        Permission::insert(
            array_filter($permissions->toArray(), 'is_int', ARRAY_FILTER_USE_KEY)
        );
    }
}
