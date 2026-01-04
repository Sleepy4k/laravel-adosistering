<?php

/**
 * Data Dummy Current User (User yang sedang login)
 * 
 * Digunakan untuk: Halaman Profil User
 * 
 * API Endpoint yang dibutuhkan:
 * - GET  /api/user/profile         - Get profil user yang login
 * - PUT  /api/user/profile         - Update profil user
 * - PUT  /api/user/password        - Update password user
 * - POST /api/user/avatar          - Upload avatar user
 * 
 * Request Body untuk PUT /api/user/profile:
 * {
 *     "nama_lengkap": "string",
 *     "email": "string",
 *     "nomor_whatsapp": "string",
 *     "bio": "string",
 *     "negara": "string",
 *     "provinsi": "string",
 *     "kota": "string",
 *     "kode_pos": "string"
 * }
 * 
 * Request Body untuk PUT /api/user/password:
 * {
 *     "password_lama": "string",
 *     "password_baru": "string",
 *     "konfirmasi_password": "string"
 * }
 */

return [
    'id' => 100,
    'username' => 'maoscilacap',
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
    'password_masked' => '**********', // Untuk display purposes
];
