<?php

namespace Database\Factories;

use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Block>
 */
class BlockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $regionId = Region::query()
            ->select('id')
            ->value('id');
        $userId = User::query()
            ->select('id')
            ->role(config('rbac.role.default'))
            ->value('id');

        $data = [
            [
                'name' => 'Block A',
                'location' => 'Location A',
            ],
            [
                'name' => 'Block B',
                'location' => 'Location B',
            ],
            [
                'name' => 'Block C',
                'location' => 'Location C',
            ],
            [
                'name' => 'Block D',
                'location' => 'Location D',
            ],
            [
                'name' => 'Block E',
                'location' => 'Location E',
            ],
            [
                'name' => 'Block F',
                'location' => 'Location F',
            ]
        ];

        $currentTime = now();
        $uuids = collect(range(1, count($data)))
            ->map(fn() => (string) Str::uuid())
            ->sort()
            ->values()
            ->all();

        foreach ($data as $index => &$entry) {
            $entry = array_merge([
                'code' => implode('', array_map(fn($word) => strtoupper($word[0]), explode(' ', $entry['name']))),
            ], $entry);

            $entry['id'] = $uuids[$index];
            $entry['region_id'] = $regionId;
            $entry['user_id'] = $userId;
            $entry['created_at'] = $currentTime;
            $entry['updated_at'] = $currentTime;
        }

        unset($entry);

        return $data;
    }
}
