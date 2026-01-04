<?php

namespace Database\Seeders;

use App\Models\UserApi;
use Illuminate\Database\Seeder;

class UserApiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (UserApi::query()->withoutCache()->count() > 0) return;

        $apis = UserApi::factory()->make();

        UserApi::query()->insert($apis->toArray());
    }
}
