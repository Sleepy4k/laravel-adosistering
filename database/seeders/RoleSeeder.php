<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Role::query()->count() > 0) return;

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = Role::factory()->make();

        collect($roles->toArray())
            ->filter(fn($value, $key) => is_int($key))
            ->each(function (array $role) {
                $permissions = $role['permissions'] ?? [];
                unset($role['permissions']);

                Role::create($role)->syncPermissions($permissions);
            });
    }
}
