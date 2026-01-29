<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IrrigationSetting>
 */
class IrrigationSettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userId = User::query()
            ->select('id')
            ->role(config('rbac.role.default'))
            ->value('id');

        $data = [
            [
                'user_id' => $userId,
                'moisture_min' => 20,
                'moisture_max' => 65,
                'moisture_dry' => 20,
                'moisture_normal' => 50,
                'moisture_wet' => 80,
                'safety_timeout_min' => 1,
                'safety_timeout_max' => 3,
            ],
        ];

        $currentTime = now();
        $uuids = collect(range(1, count($data)))
            ->map(fn() => (string) Str::uuid())
            ->sort()
            ->values()
            ->all();

        foreach ($data as $index => &$entry) {
            $entry['id'] = $uuids[$index];
            $entry['created_at'] = $currentTime;
            $entry['updated_at'] = $currentTime;
        }

        unset($entry);

        return $data;
    }
}
