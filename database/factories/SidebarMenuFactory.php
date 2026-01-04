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
                'label' => 'Dashboard',
                'icon' => 'dashboard.svg',
                'route' => 'dashboard.index',
                'active' => 'dashboard',
                'permissions' => ['dashboard.view'],
            ],
            [
                'label' => 'Manajemen User',
                'icon' => 'profile.svg',
                'route' => '#',
                'active' => 'admin.users*',
                'permissions' => ['user.view'],
            ],
            [
                'label' => 'Riwayat Irigasi',
                'icon' => 'history.svg',
                'route' => '#',
                'active' => 'irrigation.history*',
                'permissions' => ['history.view'],
            ],
            [
                'label' => 'Statistik',
                'icon' => 'statistic.svg',
                'route' => '#',
                'active' => 'statistics*',
                'permissions' => ['statistics.view'],
            ],
            [
                'label' => 'Profil',
                'icon' => 'profile.svg',
                'route' => '#',
                'active' => 'profile*',
            ],
            [
                'label' => 'Notifikasi',
                'icon' => 'notification.svg',
                'route' => '#',
                'active' => 'notifications*',
            ],
            [
                'label' => 'Pengaturan',
                'icon' => 'settings.svg',
                'route' => '#',
                'active' => 'settings*',
            ],
            [
                'label' => 'Pusat Bantuan',
                'icon' => 'help.svg',
                'route' => '#',
                'active' => 'help*',
                'is_bottom' => true,
            ],
            [
                'label' => 'Log Out',
                'icon' => 'logout.svg',
                'route' => '#',
                'active' => 'logout',
                'is_bottom' => true,
                'danger' => true,
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
