<?php

/**
 * Data Dummy Irrigation History (Riwayat Irigasi)
 * 
 * Digunakan untuk: Halaman Riwayat Irigasi User
 * 
 * API Endpoint yang dibutuhkan:
 * - GET /api/user/irrigation-history - List riwayat irigasi dengan filter
 * 
 * Query Parameters:
 * - blok: string (Blok A, Blok B, Blok C)
 * - status: string (Irigasi Selesai, Irigasi Aktif, Irigasi Gagal)
 * - jenis: string (Otomatis, Manual)
 * - tanggal: date (YYYY-MM-DD)
 * - page: int
 * - per_page: int (default: 10)
 * 
 * Database Schema:
 * CREATE TABLE irrigation_logs (
 *     id BIGINT PRIMARY KEY AUTO_INCREMENT,
 *     blok_id INT NOT NULL,
 *     sprayer_id INT NOT NULL,
 *     status ENUM('Irigasi Selesai', 'Irigasi Aktif', 'Irigasi Gagal') NOT NULL,
 *     jenis ENUM('Otomatis', 'Manual') NOT NULL,
 *     kelembaban DECIMAL(5,2),
 *     persentase_perubahan DECIMAL(5,2),
 *     total_air DECIMAL(10,2) COMMENT 'dalam Liter',
 *     debit_air DECIMAL(10,2) COMMENT 'dalam Liter/menit',
 *     durasi INT COMMENT 'dalam detik',
 *     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 *     FOREIGN KEY (blok_id) REFERENCES bloks(id),
 *     FOREIGN KEY (sprayer_id) REFERENCES sprayers(id)
 * );
 */

return [
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
];
