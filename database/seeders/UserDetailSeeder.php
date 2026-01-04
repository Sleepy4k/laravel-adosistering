<?php

namespace Database\Seeders;

use App\Models\UserDetail;
use Illuminate\Database\Seeder;

class UserDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (UserDetail::query()->withoutCache()->count() > 0) return;

        $details = UserDetail::factory()->make();

        UserDetail::query()->insert($details->toArray());
    }
}
