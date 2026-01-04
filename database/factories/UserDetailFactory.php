<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserDetail>
 */
class UserDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users = User::query()
            ->select('id')
            ->orderBy('id', 'asc')
            ->pluck('id')
            ->toArray();

        $data = [
            [
                'familiar_name' => 'Super',
                'gender' => 'male',
                'date_of_birth' => '1990-01-01',
                'other_phone' => '081234567899',
                'occupation' => 'Administrator',
                'domicile' => 'City A',
                'address' => '123 Super St, City A',
                'notes' => 'Superadmin user details.',
            ],
            [
                'familiar_name' => 'Admin',
                'gender' => 'female',
                'date_of_birth' => '1992-02-02',
                'other_phone' => '081234567898',
                'occupation' => 'Administrator',
                'domicile' => 'City B',
                'address' => '456 Admin Rd, City B',
                'notes' => 'Admin user details.',
            ],
            [
                'familiar_name' => 'User',
                'gender' => 'male',
                'date_of_birth' => '1995-03-03',
                'other_phone' => '081234567897',
                'occupation' => 'Farmer',
                'domicile' => 'City C',
                'address' => '789 User Ave, City C',
                'notes' => 'Regular user details.',
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
                'user_id' => $users[$index],
            ], $entry);

            $entry['id'] = $uuids[$index];
            $entry['created_at'] = $currentTime;
            $entry['updated_at'] = $currentTime;
        }

        unset($entry);

        return $data;
    }
}
