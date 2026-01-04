<?php

/*
|--------------------------------------------------------------------------
| Dummy Data Configuration
|--------------------------------------------------------------------------
|
| File ini adalah bridge untuk mengakses semua dummy data dari config.
| Semua data sebenarnya disimpan di folder database/data/*.php
|
| CARA PENGGUNAAN:
| 1. Di Route: $data = config('dummy.users');
| 2. Di Controller: $users = config('dummy.users');
| 3. Langsung load file: $users = include database_path('data/users.php');
|
| STRUKTUR FOLDER:
| database/data/
| ├── users.php              - Data pengguna (Admin & Superadmin)
| ├── current-user.php       - Data profil user yang login
| ├── irrigation-history.php - Riwayat irigasi
| ├── blocks.php             - Data blok dan sprayer IoT
| ├── statistics.php         - Statistik ringkasan sistem
| ├── statistik.php          - Data statistik halaman user (periode)
| └── notifications.php      - Data notifikasi
|
| CATATAN UNTUK BACKEND:
| - Setiap file di database/data/ memiliki dokumentasi API endpoint
| - Lihat header comment di masing-masing file untuk detail struktur
| - Dokumentasi lengkap ada di docs/api-*.md
|
*/

return [
    /*
    |--------------------------------------------------------------------------
    | Users Data - Untuk Manajemen User (Admin & Superadmin)
    |--------------------------------------------------------------------------
    */
    'users' => include database_path('data/users.php'),

    /*
    |--------------------------------------------------------------------------
    | Current User Profile - Data User yang Sedang Login
    |--------------------------------------------------------------------------
    */
    'current_user' => include database_path('data/current-user.php'),

    /*
    |--------------------------------------------------------------------------
    | Irrigation History - Riwayat Irigasi
    |--------------------------------------------------------------------------
    */
    'irrigation_history' => include database_path('data/irrigation-history.php'),

    /*
    |--------------------------------------------------------------------------
    | Blocks & Sprayers - Data Blok dan Sprayer untuk Dashboard
    |--------------------------------------------------------------------------
    */
    'blocks' => include database_path('data/blocks.php'),

    /*
    |--------------------------------------------------------------------------
    | Statistics - Data Statistik untuk Dashboard
    |--------------------------------------------------------------------------
    */
    'statistics' => include database_path('data/statistics.php'),
];
