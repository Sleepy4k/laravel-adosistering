<?php

namespace Database\Seeders;

use App\Models\Sprayer;
use Illuminate\Database\Seeder;

class SprayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Sprayer::query()->withoutCache()->count() > 0) return;

        $sprayers = Sprayer::factory()->make();

        Sprayer::query()->insert($sprayers->toArray());
    }
}
