<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::query()->withoutCache()->count() > 0) return;

        $users = User::factory()->make();

        collect($users->toArray())
            ->each(function (array $user) {
                $role = $user['role'] ?? null;
                unset($user['role']);

                User::create($user)->assignRole($role);
            });
    }
}
