<?php

return [
    'user' => [
        [
            'label' => 'Beranda',
            'icon' => 'home.svg',
            'route' => 'user.dashboard',
            'active' => 'user.dashboard',
        ],
        [
            'label' => 'Riwayat Irigasi',
            'icon' => 'history.svg',
            'route' => '#',
            'active' => 'user.history*',
        ],
        [
            'label' => 'Profil',
            'icon' => 'profile.svg',
            'route' => '#',
            'active' => 'user.profile*',
        ],
        [
            'label' => 'Notifikasi',
            'icon' => 'notification.svg',
            'route' => '#',
            'active' => 'user.notifications*',
        ],
        [
            'label' => 'Pengaturan',
            'icon' => 'settings.svg',
            'route' => '#',
            'active' => 'user.settings*',
        ],
    ],

    'admin' => [
        [
            'label' => 'Beranda',
            'icon' => 'home.svg',
            'route' => 'admin.dashboard',
            'active' => 'admin.dashboard',
        ],
        [
            'label' => 'Manajemen User',
            'icon' => 'profile.svg',
            'route' => 'admin.users.index',
            'active' => 'admin.users*',
        ],
        [
            'label' => 'Riwayat Irigasi',
            'icon' => 'history.svg',
            'route' => '#',
            'active' => 'admin.history*',
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
            'route' => 'superadmin.dashboard',
            'active' => 'superadmin.dashboard',
        ],
        [
            'label' => 'Manajemen User',
            'icon' => 'profile.svg',
            'route' => 'superadmin.users.index',
            'active' => 'superadmin.users*',
        ],
        [
            'label' => 'Riwayat Irigasi',
            'icon' => 'history.svg',
            'route' => '#',
            'active' => 'superadmin.history*',
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
            'route' => '#',
            'active' => 'logout',
            'danger' => true,
        ],
    ],
];
