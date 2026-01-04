<?php

/**
 * Data Dummy Notifikasi
 * 
 * Jenis Notifikasi:
 * - Kendala Teknis: Masalah sensor, koneksi, pompa, dll
 * - Kondisi Lahan: Kelembaban rendah/tinggi, kondisi tanah
 * - Irigasi: Status irigasi otomatis/manual
 * 
 * Struktur data untuk backend:
 * GET /api/user/notifications?jenis={jenis}&tanggal={YYYY-MM-DD}
 */

return [
    [
        'id' => 1,
        'jenis' => 'Kendala Teknis',
        'blok' => 'Blok D',
        'sprayer' => 'Sprayer 1',
        'pesan' => 'Irigasi dihentikan otomatis. Sistem tidak menerima data digital selama 5 menit. Mohon periksa koneksi internet, kabel pada sensor, atau hubungi tim teknis',
        'waktu' => '27 Des 2025, 09:22 WIB',
        'tanggal' => '2025-12-27',
    ],
    [
        'id' => 2,
        'jenis' => 'Kendala Teknis',
        'blok' => 'Blok A',
        'sprayer' => 'Sprayer 1',
        'pesan' => 'Sensor pompa mengalami masalah. Silahkan periksa alat atau hubungi tim teknis',
        'waktu' => '27 Des 2025, 09:22 WIB',
        'tanggal' => '2025-12-27',
    ],
    [
        'id' => 3,
        'jenis' => 'Kendala Teknis',
        'blok' => 'Blok B',
        'sprayer' => 'Sprayer 2',
        'pesan' => 'Sensor debit air mengalami masalah. Silahkan periksa alat atau hubungi tim teknis',
        'waktu' => '27 Des 2025, 09:22 WIB',
        'tanggal' => '2025-12-27',
    ],
    [
        'id' => 4,
        'jenis' => 'Kendala Teknis',
        'blok' => 'Blok E',
        'sprayer' => null,
        'pesan' => 'Sensor kelembaban tanah mengalami masalah. Silahkan periksa alat atau hubungi tim teknis',
        'waktu' => '27 Des 2025, 09:22 WIB',
        'tanggal' => '2025-12-27',
    ],
    [
        'id' => 5,
        'jenis' => 'Kondisi Lahan',
        'blok' => 'Blok C',
        'sprayer' => null,
        'pesan' => 'Kondisi kering terdeteksi (Kelembaban: 27,66%). Lakukan irigasi supaya kelembaban lahan kembali normal',
        'waktu' => '27 Des 2025, 09:22 WIB',
        'tanggal' => '2025-12-27',
    ],
    [
        'id' => 6,
        'jenis' => 'Kondisi Lahan',
        'blok' => 'Blok A',
        'sprayer' => null,
        'pesan' => 'Debit air rendah terdeteksi (Debit Air: 9,11 Liter/menit). Silahkan periksa pipa maupun sumber air',
        'waktu' => '27 Des 2025, 09:22 WIB',
        'tanggal' => '2025-12-27',
    ],
    [
        'id' => 7,
        'jenis' => 'Irigasi',
        'blok' => 'Blok A',
        'sprayer' => null,
        'pesan' => 'Irigasi otomatis selesai (Durasi: 33 menit 12 detik)',
        'waktu' => '27 Des 2025, 09:22 WIB',
        'tanggal' => '2025-12-27',
    ],
    [
        'id' => 8,
        'jenis' => 'Irigasi',
        'blok' => 'Blok A',
        'sprayer' => null,
        'pesan' => 'Irigasi otomatis dimulai',
        'waktu' => '27 Des 2025, 09:22 WIB',
        'tanggal' => '2025-12-27',
    ],
    [
        'id' => 9,
        'jenis' => 'Kondisi Lahan',
        'blok' => 'Blok B',
        'sprayer' => null,
        'pesan' => 'Kelembaban tanah sudah kembali normal (Kelembaban: 55,32%)',
        'waktu' => '26 Des 2025, 14:30 WIB',
        'tanggal' => '2025-12-26',
    ],
    [
        'id' => 10,
        'jenis' => 'Irigasi',
        'blok' => 'Blok C',
        'sprayer' => null,
        'pesan' => 'Irigasi manual selesai (Durasi: 15 menit 45 detik)',
        'waktu' => '26 Des 2025, 10:15 WIB',
        'tanggal' => '2025-12-26',
    ],
    [
        'id' => 11,
        'jenis' => 'Kendala Teknis',
        'blok' => 'Blok C',
        'sprayer' => 'Sprayer 1',
        'pesan' => 'Koneksi sensor terputus. Sistem mencoba menghubungkan kembali...',
        'waktu' => '25 Des 2025, 16:45 WIB',
        'tanggal' => '2025-12-25',
    ],
    [
        'id' => 12,
        'jenis' => 'Kondisi Lahan',
        'blok' => 'Blok D',
        'sprayer' => null,
        'pesan' => 'Kelembaban tanah terlalu tinggi (Kelembaban: 85,20%). Kurangi frekuensi irigasi',
        'waktu' => '25 Des 2025, 08:00 WIB',
        'tanggal' => '2025-12-25',
    ],
];
