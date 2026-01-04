<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Permission>
 */
class PermissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $data = config('rbac.list.permissions');

        if (empty($data)) return [];

        $currentTime = now();
        foreach ($data as $index => &$entry) {
            $tmp = !is_string($entry) ? $entry : ['name' => $entry];
            $tmp['guard_name'] = 'web';
            $tmp['created_at'] = $currentTime;
            $tmp['updated_at'] = $currentTime;
            $entry = $tmp;
        }

        return $data;
    }
}
