<?php

/**
 * Data Dummy Notifications (Notifikasi)
 * 
 * Digunakan untuk: Halaman Notifikasi User
 * 
 * API Endpoint yang dibutuhkan:
 * - GET /api/user/notifications - List notifikasi dengan filter
 * 
 * Query Parameters:
 * - jenis: string (Kendala Teknis, Kondisi Lahan, Irigasi)
 * - tanggal: date (YYYY-MM-DD)
 * - page: int
 * - per_page: int (default: 20)
 * 
 * Database Schema:
 * CREATE TABLE notifications (
 *     id BIGINT PRIMARY KEY AUTO_INCREMENT,
 *     blok_id INT NOT NULL,
 *     sprayer_id INT NULL,
 *     jenis ENUM('Kendala Teknis', 'Kondisi Lahan', 'Irigasi') NOT NULL,
 *     pesan TEXT NOT NULL,
 *     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 *     FOREIGN KEY (blok_id) REFERENCES bloks(id),
 *     FOREIGN KEY (sprayer_id) REFERENCES sprayers(id)
 * );
 */

return [
    [
        'id' => 1,
        'blok' => 'Blok B',
        'sprayer' => 'Sprayer 2',
        'jenis' => 'Kendala Teknis',
        'pesan' => 'Terdapat kendala teknis pada sensor kelembaban. Sistem tidak dapat membaca data dengan akurat. Mohon segera lakukan pengecekan perangkat.',
        'waktu' => '27 Des 2025, 14:23 WIB',
        'tanggal' => '2025-12-27',
    ],
    [
        'id' => 2,
        'blok' => 'Blok A',
        'sprayer' => null,
        'jenis' => 'Kondisi Lahan',
        'pesan' => 'Kelembaban tanah berada di level kritis (15%). Segera lakukan irigasi untuk mencegah kerusakan tanaman.',
        'waktu' => '27 Des 2025, 10:15 WIB',
        'tanggal' => '2025-12-27',
    ],
    [
        'id' => 3,
        'blok' => 'Blok C',
        'sprayer' => 'Sprayer 3',
        'jenis' => 'Irigasi',
        'pesan' => 'Irigasi otomatis telah dimulai. Target kelembaban: 65%. Estimasi waktu: 15 menit.',
        'waktu' => '27 Des 2025, 09:30 WIB',
        'tanggal' => '2025-12-27',
    ],
    [
        'id' => 4,
        'blok' => 'Blok B',
        'sprayer' => 'Sprayer 2',
        'jenis' => 'Irigasi',
        'pesan' => 'Irigasi selesai dengan sukses. Kelembaban tanah mencapai 68%. Total air yang digunakan: 216 Liter.',
        'waktu' => '26 Des 2025, 16:45 WIB',
        'tanggal' => '2025-12-26',
    ],
    [
        'id' => 5,
        'blok' => 'Blok A',
        'sprayer' => 'Sprayer 1',
        'jenis' => 'Kendala Teknis',
        'pesan' => 'Koneksi internet terputus selama 5 menit. Data irigasi tetap tersimpan di memori lokal dan akan disinkronkan.',
        'waktu' => '26 Des 2025, 12:20 WIB',
        'tanggal' => '2025-12-26',
    ],
    [
        'id' => 6,
        'blok' => 'Blok C',
        'sprayer' => null,
        'jenis' => 'Kondisi Lahan',
        'pesan' => 'Kelembaban tanah optimal (72%). Tidak diperlukan irigasi untuk saat ini.',
        'waktu' => '25 Des 2025, 18:00 WIB',
        'tanggal' => '2025-12-25',
    ],
    [
        'id' => 7,
        'blok' => 'Blok A',
        'sprayer' => 'Sprayer 1',
        'jenis' => 'Irigasi',
        'pesan' => 'Irigasi gagal karena tekanan air tidak mencukupi. Mohon periksa pompa air.',
        'waktu' => '25 Des 2025, 14:10 WIB',
        'tanggal' => '2025-12-25',
    ],
    [
        'id' => 8,
        'blok' => 'Blok B',
        'sprayer' => 'Sprayer 2',
        'jenis' => 'Kendala Teknis',
        'pesan' => 'Baterai sprayer lemah (15%). Segera lakukan pengisian atau penggantian baterai.',
        'waktu' => '24 Des 2025, 11:35 WIB',
        'tanggal' => '2025-12-24',
    ],
    [
        'id' => 9,
        'blok' => 'Blok C',
        'sprayer' => null,
        'jenis' => 'Kondisi Lahan',
        'pesan' => 'Kelembaban tanah menurun drastis dalam 2 jam (dari 60% ke 35%). Kemungkinan terjadi kebocoran atau cuaca ekstrem.',
        'waktu' => '23 Des 2025, 15:50 WIB',
        'tanggal' => '2025-12-23',
    ],
    [
        'id' => 10,
        'blok' => 'Blok A',
        'sprayer' => 'Sprayer 1',
        'jenis' => 'Irigasi',
        'pesan' => 'Irigasi manual telah diaktifkan oleh admin. Durasi: 20 menit.',
        'waktu' => '22 Des 2025, 08:15 WIB',
        'tanggal' => '2025-12-22',
    ],
];
