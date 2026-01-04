<?php

/**
 * Data Dummy Users
 * 
 * Digunakan untuk: Manajemen User (Admin & Superadmin)
 * 
 * API Endpoint yang dibutuhkan:
 * - GET    /api/users              - List semua users dengan pagination & filter
 * - GET    /api/users/{id}         - Detail user
 * - POST   /api/users              - Create user baru
 * - PUT    /api/users/{id}         - Update user
 * - DELETE /api/users/{id}         - Delete user (soft delete)
 * 
 * Query Parameters untuk GET /api/users:
 * - search: string (nama, email, whatsapp)
 * - role: string (petani, admin, superadmin)
 * - status: string (aktif, nonaktif)
 * - jenis_pengguna: string (Individu, Kelompok Tani)
 * - page: int
 * - per_page: int (default: 10)
 * 
 * Database Schema:
 * CREATE TABLE users (
 *     id BIGINT PRIMARY KEY AUTO_INCREMENT,
 *     nama_pengguna VARCHAR(100) NOT NULL,
 *     email VARCHAR(100) UNIQUE NOT NULL,
 *     nomor_whatsapp VARCHAR(20),
 *     role ENUM('petani', 'admin', 'superadmin') DEFAULT 'petani',
 *     jenis_pengguna ENUM('Individu', 'Kelompok Tani') DEFAULT 'Individu',
 *     status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
 *     nama_panggilan VARCHAR(50),
 *     jenis_kelamin ENUM('Laki-laki', 'Perempuan'),
 *     tanggal_lahir DATE,
 *     hp_lain VARCHAR(20),
 *     pekerjaan VARCHAR(100),
 *     wilayah VARCHAR(100),
 *     alamat_lengkap TEXT,
 *     catatan_internal TEXT,
 *     username VARCHAR(50) UNIQUE NOT NULL,
 *     password VARCHAR(255) NOT NULL,
 *     api_key VARCHAR(100),
 *     profile_image VARCHAR(255),
 *     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 *     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 *     deleted_at TIMESTAMP NULL
 * );
 */

return [
    [
        'id' => 1,
        'nama_pengguna' => 'Budi Santoso',
        'email' => 'budi.santoso@gmail.com',
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
        'profile_image' => null,
        'created_at' => '2025-01-15',
    ],
    [
        'id' => 2,
        'nama_pengguna' => 'Siti Nurhaliza',
        'email' => 'siti.nurhaliza@gmail.com',
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
        'profile_image' => null,
        'created_at' => '2025-02-10',
    ],
    [
        'id' => 3,
        'nama_pengguna' => 'Ahmad Wijaya',
        'email' => 'ahmad.wijaya@gmail.com',
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
        'profile_image' => null,
        'created_at' => '2025-01-20',
    ],
    [
        'id' => 4,
        'nama_pengguna' => 'Dewi Lestari',
        'email' => 'dewi.lestari@gmail.com',
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
        'profile_image' => null,
        'created_at' => '2025-03-01',
    ],
    [
        'id' => 5,
        'nama_pengguna' => 'Eko Prasetyo',
        'email' => 'eko.prasetyo@gmail.com',
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
        'profile_image' => null,
        'created_at' => '2024-12-01',
    ],
    [
        'id' => 6,
        'nama_pengguna' => 'Muchtarom',
        'email' => 'muchtarom@gmail.com',
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
        'profile_image' => null,
        'created_at' => '2025-04-01',
    ],
];
