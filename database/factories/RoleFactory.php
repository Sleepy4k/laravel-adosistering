<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $data = config('rbac.list.roles');

        if (empty($data)) return [];

        $currentTime = now();

        foreach ($data as $index => &$entry) {
            $entry = !is_string($entry) ? $entry : ['name' => $entry];
            $entry['guard_name'] = 'web';
            $entry['created_at'] = $currentTime;
            $entry['updated_at'] = $currentTime;

            $permissions = config("rbac.permissions.{$entry['name']}", []);

            if (is_string($permissions) && in_array(strtolower($permissions), ['*', 'all'])) {
                $permissions = config('rbac.list.permissions', []);
            }

            $entry['permissions'] = $permissions;
        }

        return $data;
    }
}
