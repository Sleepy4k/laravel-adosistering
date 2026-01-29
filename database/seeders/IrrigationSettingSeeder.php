<?php

namespace Database\Seeders;

use App\Models\IrrigationSetting;
use Illuminate\Database\Seeder;

class IrrigationSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (IrrigationSetting::query()->withoutCache()->count() > 0) return;

        $settings = IrrigationSetting::factory()->make();

        IrrigationSetting::query()->insert($settings->toArray());
    }
}
