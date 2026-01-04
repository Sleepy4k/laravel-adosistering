<?php

namespace Database\Seeders;

use App\Models\Coordinate;
use Illuminate\Database\Seeder;

class CoordinateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Coordinate::query()->withoutCache()->count() > 0) return;

        $coordinates = Coordinate::factory()->make();

        Coordinate::query()->insert($coordinates->toArray());
    }
}
