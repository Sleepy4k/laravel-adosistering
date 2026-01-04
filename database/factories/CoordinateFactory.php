<?php

namespace Database\Factories;

use App\Models\Block;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coordinate>
 */
class CoordinateFactory extends Factory
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
                'marker' => 'A',
                'color' => '#FF0000',
                'points' => [
                    ['lat' => -6.200000, 'lng' => 106.816666],
                    ['lat' => -6.201000, 'lng' => 106.817666],
                    ['lat' => -6.202000, 'lng' => 106.818666],
                ]
            ],
            [
                'marker' => 'B',
                'color' => '#00FF00',
                'points' => [
                    ['lat' => -6.210000, 'lng' => 106.826666],
                    ['lat' => -6.210000, 'lng' => 106.828666],
                    ['lat' => -6.212000, 'lng' => 106.828666],
                    ['lat' => -6.212000, 'lng' => 106.826666],
                ]
            ],
            [
                'marker' => 'C',
                'color' => '#0000FF',
                'points' => [
                    ['lat' => -6.220000, 'lng' => 106.836666],
                    ['lat' => -6.221000, 'lng' => 106.837666],
                    ['lat' => -6.221000, 'lng' => 106.839666],
                    ['lat' => -6.220000, 'lng' => 106.840666],
                    ['lat' => -6.219000, 'lng' => 106.839666],
                    ['lat' => -6.219000, 'lng' => 106.837666],
                ]
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
            ], $entry);

            $entry['id'] = $uuids[$index];
            $entry['points'] = json_encode($entry['points']);
            $entry['created_at'] = $currentTime;
            $entry['updated_at'] = $currentTime;
        }

        unset($entry);

        return $data;
    }
}
