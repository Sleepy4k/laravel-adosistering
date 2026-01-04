<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (UserType::query()->withoutCache()->count() > 0) return;

        $types = UserType::factory()->make();

        UserType::query()->insert($types->toArray());
    }
}
