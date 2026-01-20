<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SidebarMenu>
 */
class SidebarMenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $data = [
            [
                'label' => 'Beranda',
                'icon' => 'home.svg',
                'route' => 'home',
                'active' => 'home',
                'permissions' => ['dashboard.view'],
            ],
            [
                'label' => 'Manajemen User',
                'icon' => 'profile.svg',
                'route' => 'users.index',
                'active' => 'users*',
                'permissions' => ['user.view'],
            ],
            [
                'label' => 'Riwayat Irigasi',
                'icon' => 'history.svg',
                'route' => 'irrigation-history',
                'active' => 'irrigation-history*',
                'permissions' => ['history.view'],
            ],
            [
                'label' => 'Statistik',
                'icon' => 'statistic.svg',
                'route' => '#',
                'active' => 'statistics*',
                'permissions' => ['statistic.view'],
            ],
            [
                'label' => 'Profil',
                'icon' => 'profile.svg',
                'route' => 'profile.index',
                'active' => 'profile*',
                'permissions' => ['profile.view'],
            ],
            [
                'label' => 'Notifikasi',
                'icon' => 'notification.svg',
                'route' => '#',
                'active' => 'notifications*',
                'permissions' => [],
            ],
            [
                'label' => 'Pengaturan',
                'icon' => 'settings.svg',
                'route' => '#',
                'active' => 'settings*',
                'permissions' => [],
            ],
            [
                'label' => 'Pusat Bantuan',
                'icon' => 'help.svg',
                'route' => '#',
                'active' => 'help*',
                'is_bottom' => true,
                'permissions' => [],
            ],
            [
                'label' => 'Log Out',
                'icon' => 'logout.svg',
                'route' => 'logout',
                'active' => 'logout',
                'is_bottom' => true,
                'danger' => true,
                'permissions' => [],
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
                'permissions' => [],
                'is_bottom' => false,
                'danger' => false,
            ], $entry);

            $entry['id'] = $uuids[$index];
            $entry['permissions'] = json_encode($entry['permissions']);
            $entry['created_at'] = $currentTime;
            $entry['updated_at'] = $currentTime;
        }

        unset($entry);

        return $data;
    }
}
