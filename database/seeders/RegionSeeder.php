<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Region::query()->withoutCache()->count() > 0) return;

        $regions = Region::factory()->make();

        Region::query()->insert($regions->toArray());
    }
}
