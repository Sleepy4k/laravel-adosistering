<?php

namespace Database\Factories;

use App\Models\Block;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sprayer>
 */
class SprayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $blocks = Block::query()
            ->select('id')
            ->orderBy('id', 'asc')
            ->pluck('id')
            ->toArray();

        $data = [
            [
                'name' => 'Sprayer A',
            ],
            [
                'name' => 'Sprayer B',
            ],
            [
                'name' => 'Sprayer C',
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
                'block_id' => $blocks[$index],
                'is_pump_on' => false,
                'is_auto_irrigation' => false,
            ], $entry);

            $entry['id'] = $uuids[$index];
            $entry['created_at'] = $currentTime;
            $entry['updated_at'] = $currentTime;
        }

        unset($entry);

        return $data;
    }
}
