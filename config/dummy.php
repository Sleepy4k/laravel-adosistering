<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Dummy Data untuk Frontend Development
    |--------------------------------------------------------------------------
    |
    | File ini berisi semua data dummy yang digunakan untuk pengembangan frontend.
    | Backend developer dapat mengganti data ini dengan data dari database.
    |
    | CARA PENGGUNAAN:
    | 1. Di Route: $data = config('dummy.users'); return view('view', compact('data'));
    | 2. Di View: @json($data) atau {{ $data['key'] }}
    | 3. Dengan Alpine.js: x-data="{ items: @json($data) }"
    |
    | STRUKTUR DATA:
    | - users: Data pengguna untuk manajemen user (Admin & Superadmin)
    | - current_user: Data profil user yang sedang login
    | - irrigation_history: Data riwayat irigasi dengan filter tanggal
    | - blocks: Data blok dan sprayer untuk dashboard IoT
    | - statistics: Data statistik ringkasan sistem
    |
    | CATATAN PENTING:
    | - Beberapa field memiliki alias untuk compatibility (name/nama_pengguna, whatsapp/nomor_whatsapp)
    | - Field dengan camelCase digunakan di Alpine.js component props
    | - Field dengan snake_case digunakan di database/backend
    | - Setelah update config, jalankan: php artisan config:clear
    |
    | FILTER OPTIONS (INLINE):
    | - Filter dropdown (Blok, Status, Jenis, Role, dll) dibuat inline di view
    | - Alasan: Terkait erat dengan UI styling, tidak perlu dinamis dari database
    | - Lokasi: Hardcoded langsung di blade component masing-masing
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Users Data - Untuk Manajemen User (Admin & Superadmin)
    |--------------------------------------------------------------------------
    */
    'users' => [
        [
            'id' => 1,
            'name' => 'Budi Santoso', // Alias untuk compatibility dengan view
            'nama_pengguna' => 'Budi Santoso',
            'email' => 'budi.santoso@gmail.com',
            'whatsapp' => '081234567890', // Alias untuk compatibility dengan view
            'nomor_whatsapp' => '081234567890',
            'role' => 'petani',
            'jenis_pengguna' => 'Individu',
            'status' => 'aktif',
            'nama_panggilan' => 'Budi',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '1985-05-15',
            'hp_lain' => '',
            'pekerjaan' => 'Petani',
            'wilayah' => 'Cilacap',
            'alamat_lengkap' => 'Desa Mernek, Kecamatan Maos, Kabupaten Cilacap',
            'catatan_internal' => '',
            'username' => 'budisantoso',
            'password' => 'password123',
            'api_key' => 'API-BUDI-001',
            'profileImage' => null, // Alias untuk compatibility dengan view
            'profile_image' => null,
            'created_at' => '2025-01-15',
        ],
        [
            'id' => 2,
            'name' => 'Siti Nurhaliza',
            'nama_pengguna' => 'Siti Nurhaliza',
            'email' => 'siti.nurhaliza@gmail.com',
            'whatsapp' => '081234567891',
            'nomor_whatsapp' => '081234567891',
            'role' => 'petani',
            'jenis_pengguna' => 'Individu',
            'status' => 'aktif',
            'nama_panggilan' => 'Siti',
            'jenis_kelamin' => 'Perempuan',
            'tanggal_lahir' => '1990-08-20',
            'hp_lain' => '',
            'pekerjaan' => 'Petani',
            'wilayah' => 'Purbalingga',
            'alamat_lengkap' => 'Desa Bojong, Kecamatan Purbalingga',
            'catatan_internal' => '',
            'username' => 'sitinurhaliza',
            'password' => 'password123',
            'api_key' => 'API-SITI-002',
            'profileImage' => null,
            'profile_image' => null,
            'created_at' => '2025-02-10',
        ],
        [
            'id' => 3,
            'name' => 'Ahmad Wijaya',
            'nama_pengguna' => 'Ahmad Wijaya',
            'email' => 'ahmad.wijaya@gmail.com',
            'whatsapp' => '081234567892',
            'nomor_whatsapp' => '081234567892',
            'role' => 'admin',
            'jenis_pengguna' => 'Individu',
            'status' => 'nonaktif',
            'nama_panggilan' => 'Ahmad',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '1988-03-10',
            'hp_lain' => '087654321098',
            'pekerjaan' => 'Administrator',
            'wilayah' => 'Banyumas',
            'alamat_lengkap' => 'Jl. Jendral Soedirman No. 123, Purwokerto',
            'catatan_internal' => 'Admin area Banyumas',
            'username' => 'ahmadwijaya',
            'password' => 'password123',
            'api_key' => 'API-AHMAD-003',
            'profileImage' => null,
            'profile_image' => null,
            'created_at' => '2025-01-20',
        ],
        [
            'id' => 4,
            'name' => 'Dewi Lestari',
            'nama_pengguna' => 'Dewi Lestari',
            'email' => 'dewi.lestari@gmail.com',
            'whatsapp' => '081234567893',
            'nomor_whatsapp' => '081234567893',
            'role' => 'petani',
            'jenis_pengguna' => 'Kelompok Tani',
            'status' => 'aktif',
            'nama_panggilan' => 'Dewi',
            'jenis_kelamin' => 'Perempuan',
            'tanggal_lahir' => '1992-12-05',
            'hp_lain' => '',
            'pekerjaan' => 'Ketua Kelompok Tani',
            'wilayah' => 'Kebumen',
            'alamat_lengkap' => 'Desa Sruweng, Kecamatan Sruweng, Kebumen',
            'catatan_internal' => 'Ketua Kelompok Tani Makmur Jaya',
            'username' => 'dewilestari',
            'password' => 'password123',
            'api_key' => 'API-DEWI-004',
            'profileImage' => null,
            'profile_image' => null,
            'created_at' => '2025-03-01',
        ],
        [
            'id' => 5,
            'name' => 'Eko Prasetyo',
            'nama_pengguna' => 'Eko Prasetyo',
            'email' => 'eko.prasetyo@gmail.com',
            'whatsapp' => '081234567894',
            'nomor_whatsapp' => '081234567894',
            'role' => 'superadmin',
            'jenis_pengguna' => 'Individu',
            'status' => 'aktif',
            'nama_panggilan' => 'Eko',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '1980-07-25',
            'hp_lain' => '089876543210',
            'pekerjaan' => 'System Administrator',
            'wilayah' => 'Semarang',
            'alamat_lengkap' => 'Jl. Pemuda No. 45, Semarang',
            'catatan_internal' => 'Super Admin utama sistem',
            'username' => 'ekoprasetyo',
            'password' => 'password123',
            'api_key' => 'API-EKO-005',
            'profileImage' => null,
            'profile_image' => null,
            'created_at' => '2024-12-01',
        ],
        [
            'id' => 6,
            'name' => 'Muchtarom',
            'nama_pengguna' => 'Muchtarom',
            'email' => 'muchtarom@gmail.com',
            'whatsapp' => '081234567890',
            'nomor_whatsapp' => '081234567890',
            'role' => 'petani',
            'jenis_pengguna' => 'Individu',
            'status' => 'aktif',
            'nama_panggilan' => '',
            'jenis_kelamin' => '',
            'tanggal_lahir' => '',
            'hp_lain' => '',
            'pekerjaan' => '',
            'wilayah' => 'Purbalingga',
            'alamat_lengkap' => '',
            'catatan_internal' => '',
            'username' => 'muchtarom01',
            'password' => 'muchtarom123',
            'api_key' => '',
            'profileImage' => null,
            'profile_image' => null,
            'created_at' => '2025-04-01',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Current User Profile - Data User yang Sedang Login
    |--------------------------------------------------------------------------
    */
    'current_user' => [
        'id' => 100,
        'nama_lengkap' => 'Ahmad Subardjo',
        'email' => 'ahmadsubardjo@gmail.com',
        'nomor_whatsapp' => '081234567890',
        'bio' => 'Pengelola Kawista',
        'role' => 'petani',
        'negara' => 'Indonesia',
        'provinsi' => 'Jawa Tengah',
        'kota' => 'Cilacap',
        'kode_pos' => '40152',
        'profile_image' => '/assets/images/default-avatar.jpg',
    ],

    /*
    |--------------------------------------------------------------------------
    | Irrigation History - Riwayat Irigasi
    |--------------------------------------------------------------------------
    */
    'irrigation_history' => [
        [
            'id' => 1,
            'blok' => 'Blok B',
            'status' => 'Irigasi Selesai',
            'jenis' => 'Otomatis',
            'sprayer' => 'Sprayer 1',
            'kelembaban' => '61,12%',
            'persentase' => '+13,23%',
            'total_air' => '216 Liter',
            'debit_air' => '74,52 Liter/menit',
            'durasi' => '15:09 menit',
            'waktu' => '27 Des 2025, 11:05 WIB',
            'tanggal' => '2025-12-27',
        ],
        [
            'id' => 2,
            'blok' => 'Blok A',
            'status' => 'Irigasi Selesai',
            'jenis' => 'Manual',
            'sprayer' => 'Sprayer 2',
            'kelembaban' => '64,22%',
            'persentase' => '+22,23%',
            'total_air' => '210 Liter',
            'debit_air' => '70,00 Liter/menit',
            'durasi' => '14:30 menit',
            'waktu' => '26 Des 2025, 11:12 WIB',
            'tanggal' => '2025-12-26',
        ],
        [
            'id' => 3,
            'blok' => 'Blok C',
            'status' => 'Irigasi Gagal',
            'jenis' => 'Otomatis',
            'sprayer' => 'Sprayer 3',
            'kelembaban' => '35,00%',
            'persentase' => null,
            'total_air' => null,
            'debit_air' => null,
            'durasi' => null,
            'waktu' => '25 Des 2025, 10:55 WIB',
            'tanggal' => '2025-12-25',
        ],
        [
            'id' => 4,
            'blok' => 'Blok B',
            'status' => 'Irigasi Aktif',
            'jenis' => 'Manual',
            'sprayer' => 'Sprayer 1',
            'kelembaban' => '47,89%',
            'persentase' => null,
            'total_air' => null,
            'debit_air' => null,
            'durasi' => null,
            'waktu' => '24 Des 2025, 10:50 WIB',
            'tanggal' => '2025-12-24',
        ],
        [
            'id' => 5,
            'blok' => 'Blok A',
            'status' => 'Irigasi Selesai',
            'jenis' => 'Otomatis',
            'sprayer' => 'Sprayer 2',
            'kelembaban' => '58,45%',
            'persentase' => '+18,50%',
            'total_air' => '195 Liter',
            'debit_air' => '65,00 Liter/menit',
            'durasi' => '13:00 menit',
            'waktu' => '23 Des 2025, 09:30 WIB',
            'tanggal' => '2025-12-23',
        ],
        [
            'id' => 6,
            'blok' => 'Blok C',
            'status' => 'Irigasi Selesai',
            'jenis' => 'Manual',
            'sprayer' => 'Sprayer 3',
            'kelembaban' => '52,30%',
            'persentase' => '+15,60%',
            'total_air' => '180 Liter',
            'debit_air' => '60,00 Liter/menit',
            'durasi' => '12:00 menit',
            'waktu' => '22 Des 2025, 14:20 WIB',
            'tanggal' => '2025-12-22',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Blocks & Sprayers - Data Blok dan Sprayer untuk Dashboard
    |--------------------------------------------------------------------------
    */
    'blocks' => [
        [
            'blockId' => 'A',
            'blockName' => 'Blok A',
            'avgHumidity' => '47,38%',
            'avgFlowRate' => '38,57 Liter / Menit',
            'totalVolume' => '78 Liter',
            'sprayers' => [
                [
                    'id' => '1',
                    'name' => 'Sprayer 1',
                    'location' => 'Desa Mernek, Kecamatan Maos, Kabupaten Cilacap, Jawa Tengah',
                    'sensorStatus' => 'Terhubung',
                    'humidity' => '47,38%',
                    'flowRate' => '33 L / Menit',
                    'volume' => '78 Liter',
                    'pumpStatus' => 'Aktif',
                    'lastUpdate' => '5 menit yang lalu',
                    'isPumpOn' => true,
                    'isAutoIrrigation' => false,
                ],
                [
                    'id' => '2',
                    'name' => 'Sprayer 2',
                    'location' => 'Desa Mernek, Kecamatan Maos, Kabupaten Cilacap, Jawa Tengah',
                    'sensorStatus' => 'Gangguan Sensor',
                    'humidity' => '47,38%',
                    'flowRate' => '33 L / Menit',
                    'volume' => '78 Liter',
                    'pumpStatus' => 'Mati',
                    'lastUpdate' => '5 menit yang lalu',
                    'isPumpOn' => false,
                    'isAutoIrrigation' => false,
                ],
            ],
        ],
        [
            'blockId' => 'B',
            'blockName' => 'Blok B',
            'avgHumidity' => '52,15%',
            'avgFlowRate' => '35,20 Liter / Menit',
            'totalVolume' => '65 Liter',
            'sprayers' => [
                [
                    'id' => '3',
                    'name' => 'Sprayer 1',
                    'location' => 'Desa Mernek, Kecamatan Maos, Kabupaten Cilacap, Jawa Tengah',
                    'sensorStatus' => 'Terhubung',
                    'humidity' => '52,15%',
                    'flowRate' => '35 L / Menit',
                    'volume' => '65 Liter',
                    'pumpStatus' => 'Mati',
                    'lastUpdate' => '3 menit yang lalu',
                    'isPumpOn' => false,
                    'isAutoIrrigation' => false,
                ],
                [
                    'id' => '4',
                    'name' => 'Sprayer 2',
                    'location' => 'Desa Mernek, Kecamatan Maos, Kabupaten Cilacap, Jawa Tengah',
                    'sensorStatus' => 'Terhubung',
                    'humidity' => '52,15%',
                    'flowRate' => '35 L / Menit',
                    'volume' => '65 Liter',
                    'pumpStatus' => 'Mati',
                    'lastUpdate' => '3 menit yang lalu',
                    'isPumpOn' => false,
                    'isAutoIrrigation' => false,
                ],
            ],
        ],
        [
            'blockId' => 'C',
            'blockName' => 'Blok C',
            'avgHumidity' => '45,00%',
            'avgFlowRate' => '30,00 Liter / Menit',
            'totalVolume' => '50 Liter',
            'sprayers' => [
                [
                    'id' => '5',
                    'name' => 'Sprayer 3',
                    'location' => 'Desa Mernek, Kecamatan Maos, Kabupaten Cilacap, Jawa Tengah',
                    'sensorStatus' => 'Terhubung',
                    'humidity' => '45,00%',
                    'flowRate' => '30 L / Menit',
                    'volume' => '50 Liter',
                    'pumpStatus' => 'Mati',
                    'lastUpdate' => '10 menit yang lalu',
                    'isPumpOn' => false,
                    'isAutoIrrigation' => true,
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Statistics - Data Statistik untuk Dashboard
    |--------------------------------------------------------------------------
    */
    'statistics' => [
        'total_users' => 6,
        'total_petani' => 4,
        'total_admin' => 1,
        'total_superadmin' => 1,
        'total_blocks' => 3,
        'total_sprayers' => 5,
        'active_irrigations' => 1,
        'completed_irrigations' => 4,
        'failed_irrigations' => 1,
    ],
];
