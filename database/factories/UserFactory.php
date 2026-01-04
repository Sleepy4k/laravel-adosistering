<?php

namespace Database\Factories;

use App\Models\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $typeId = UserType::query()
            ->where('name', 'Individu')
            ->value('id');

        $data = [
            [
                'name' => 'Superadmin User',
                'email' => 'superadmin@test.com',
                'phone' => '081234567890',
                'role' => 'superadmin',
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@test.com',
                'phone' => '081234567891',
                'role' => 'admin',
            ],
            [
                'name' => 'Regular User',
                'email' => 'user@test.com',
                'phone' => '081234567892',
                'role' => 'user',
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
                'password' => static::$password ??= 'password123',
            ], $entry);

            $entry['id'] = $uuids[$index];
            $entry['user_type_id'] = $typeId;
            $entry['created_at'] = $currentTime;
            $entry['updated_at'] = $currentTime;
        }

        unset($entry);

        return $data;
    }
}
