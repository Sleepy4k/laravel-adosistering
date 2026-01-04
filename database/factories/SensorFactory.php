<?php

namespace Database\Factories;

use App\Models\Sprayer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sensor>
 */
class SensorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sprayers = Sprayer::query()
            ->select('id')
            ->orderBy('id', 'asc')
            ->pluck('id')
            ->toArray();

        $data = [
            [
                'humidity' => 43.5,
                'flow_rate' => 12.3,
                'volume' => 150.0,
                'status' => 'online',
            ],
            [
                'humidity' => 50.2,
                'flow_rate' => 10.8,
                'volume' => 120.5,
                'status' => 'online',
            ],
            [
                'status' => 'offline',
            ],
        ];

        $currentTime = now();
        $uuids = collect(range(1, count($data)))
            ->map(fn() => (string) Str::uuid())
            ->sort()
            ->values()
            ->all();

        foreach ($data as $index => &$entry) {
            $entry = array_merge([
                'sprayer_id' => $sprayers[$index],
                'humidity' => 0,
                'flow_rate' => 0,
                'volume' => 0,
                'status' => 'offline',
            ], $entry);

            $entry['id'] = $uuids[$index];
            $entry['created_at'] = $currentTime;
            $entry['updated_at'] = $currentTime;
        }

        unset($entry);

        return $data;
    }
}
