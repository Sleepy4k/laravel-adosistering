<?php

namespace Database\Seeders;

use App\Models\Sensor;
use Illuminate\Database\Seeder;

class SensorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Sensor::query()->withoutCache()->count() > 0) return;

        $sensors = Sensor::factory()->make();

        Sensor::query()->insert($sensors->toArray());
    }
}
