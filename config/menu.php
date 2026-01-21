<?php

return [
    'user' => [
        [
            'label' => 'Beranda',
            'icon' => 'home.svg',
            'route' => 'home',
            'active' => 'home',
        ],
        [
            'label' => 'Riwayat Irigasi',
            'icon' => 'history.svg',
            'route' => 'irrigation-history',
            'active' => 'irrigation-history*',
        ],
        [
            'label' => 'Profil',
            'icon' => 'profile.svg',
            'route' => 'profile.index',
            'active' => 'profile*',
        ],
        [
            'label' => 'Statistik',
            'icon' => 'statistic.svg',
            'route' => 'user.statistik',
            'active' => 'user.statistik*',
        ],
        [
            'label' => 'Notifikasi',
            'icon' => 'notification.svg',
            'route' => 'user.notifications',
            'active' => 'user.notifications*',
        ],
        [
            'label' => 'Pengaturan',
            'icon' => 'settings.svg',
            'route' => 'user.pengaturan',
            'active' => 'user.pengaturan*',
        ],
    ],

    'admin' => [
        [
            'label' => 'Beranda',
            'icon' => 'home.svg',
            'route' => 'home',
            'active' => 'home',
        ],
        [
            'label' => 'Manajemen User',
            'icon' => 'profile.svg',
            'route' => 'users.index',
            'active' => 'users*',
        ],
        [
            'label' => 'Riwayat Irigasi',
            'icon' => 'history.svg',
            'route' => 'irrigation-history',
            'active' => 'irrigation-history*',
        ],
        [
            'label' => 'Statistik',
            'icon' => 'statistic.svg',
            'route' => '#',
            'active' => 'admin.statistics*',
        ],
        [
            'label' => 'Notifikasi',
            'icon' => 'notification.svg',
            'route' => '#',
            'active' => 'admin.notifications*',
        ],
        [
            'label' => 'Pengaturan',
            'icon' => 'settings.svg',
            'route' => '#',
            'active' => 'admin.settings*',
        ],
    ],

    'superadmin' => [
        [
            'label' => 'Beranda',
            'icon' => 'home.svg',
            'route' => 'home',
            'active' => 'home',
        ],
        [
            'label' => 'Manajemen User',
            'icon' => 'profile.svg',
            'route' => 'users.index',
            'active' => 'users*',
        ],
        [
            'label' => 'Riwayat Irigasi',
            'icon' => 'history.svg',
            'route' => 'irrigation-history',
            'active' => 'irrigation-history*',
        ],
        [
            'label' => 'Statistik',
            'icon' => 'statistic.svg',
            'route' => '#',
            'active' => 'superadmin.statistics*',
        ],
        [
            'label' => 'Notifikasi',
            'icon' => 'notification.svg',
            'route' => '#',
            'active' => 'superadmin.notifications*',
        ],
        [
            'label' => 'Pengaturan',
            'icon' => 'settings.svg',
            'route' => '#',
            'active' => 'superadmin.settings*',
        ],
    ],

    'bottom' => [
        [
            'label' => 'Pusat Bantuan',
            'icon' => 'help.svg',
            'route' => '#',
            'active' => 'help*',
        ],
        [
            'label' => 'Log Out',
            'icon' => 'logout.svg',
            'route' => 'logout',
            'active' => 'logout',
            'danger' => true,
            'logout' => true,
        ],
    ],
];
