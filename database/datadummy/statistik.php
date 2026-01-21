<?php

/**
 * Data Dummy Statistik
 * 
 * Digunakan untuk: Halaman Statistik User
 * 
 * API Endpoint yang dibutuhkan:
 * - GET /api/user/statistik - Statistik berdasarkan period (today, 7days, 30days)
 * 
 * Query Parameters:
 * - period: string (today, 7days, 30days)
 * 
 * Database Schema:
 * Data ini merupakan agregasi dari irrigation_logs, sensor_readings, dan sprayer_logs
 */

return [
    'today' => [
        'summary' => [
            'total_penggunaan_air' => 1162.53, // Total liter untuk hari ini
            'kelembaban_rata_rata' => 51.63,   // Rata-rata kelembaban semua blok
        ],
        'bloks' => [
            [
                'id' => 1,
                'nama' => 'Blok A',
                'frekuensi_irigasi' => [
                    'otomatis' => 4,
                    'manual' => 4,
                    'total' => 8,
                ],
                'kelembaban' => [
                    'rata_rata' => 47.83,
                    'min' => 31,
                    'max' => 52,
                    'status' => 'Lembab', // Kering, Lembab, Basah
                ],
                'total_air_digunakan' => 196.51,
                'debit_air_rata_rata' => 119.37,
                'chart_kelembaban' => [
                    ['waktu' => '06:00', 'nilai' => 8],
                    ['waktu' => '08:00', 'nilai' => 20],
                    ['waktu' => '10:00', 'nilai' => 28],
                    ['waktu' => '12:00', 'nilai' => 15],
                    ['waktu' => '14:00', 'nilai' => 35],
                    ['waktu' => '16:00', 'nilai' => 40],
                    ['waktu' => '18:00', 'nilai' => 52],
                    ['waktu' => '20:00', 'nilai' => 32],
                    ['waktu' => '22:00', 'nilai' => 65],
                    ['waktu' => '00:00', 'nilai' => 55],
                ],
                'chart_penggunaan_air' => [
                    ['waktu' => '06:00', 'nilai' => 100],
                    ['waktu' => '08:00', 'nilai' => 220],
                    ['waktu' => '10:00', 'nilai' => 150],
                    ['waktu' => '12:00', 'nilai' => 280],
                    ['waktu' => '14:00', 'nilai' => 320],
                    ['waktu' => '16:00', 'nilai' => 410],
                    ['waktu' => '18:00', 'nilai' => 240],
                    ['waktu' => '20:00', 'nilai' => 640],
                    ['waktu' => '22:00', 'nilai' => 430],
                    ['waktu' => '00:00', 'nilai' => 520],
                ],
            ],
            [
                'id' => 2,
                'nama' => 'Blok B',
                'frekuensi_irigasi' => [
                    'otomatis' => 5,
                    'manual' => 3,
                    'total' => 8,
                ],
                'kelembaban' => [
                    'rata_rata' => 52.45,
                    'min' => 35,
                    'max' => 68,
                    'status' => 'Lembab',
                ],
                'total_air_digunakan' => 485.52,
                'debit_air_rata_rata' => 118.25,
                'chart_kelembaban' => [
                    ['waktu' => '06:00', 'nilai' => 12],
                    ['waktu' => '08:00', 'nilai' => 25],
                    ['waktu' => '10:00', 'nilai' => 32],
                    ['waktu' => '12:00', 'nilai' => 42],
                    ['waktu' => '14:00', 'nilai' => 48],
                    ['waktu' => '16:00', 'nilai' => 55],
                    ['waktu' => '18:00', 'nilai' => 62],
                    ['waktu' => '20:00', 'nilai' => 58],
                    ['waktu' => '22:00', 'nilai' => 68],
                    ['waktu' => '00:00', 'nilai' => 60],
                ],
                'chart_penggunaan_air' => [
                    ['waktu' => '06:00', 'nilai' => 120],
                    ['waktu' => '08:00', 'nilai' => 180],
                    ['waktu' => '10:00', 'nilai' => 240],
                    ['waktu' => '12:00', 'nilai' => 320],
                    ['waktu' => '14:00', 'nilai' => 380],
                    ['waktu' => '16:00', 'nilai' => 450],
                    ['waktu' => '18:00', 'nilai' => 520],
                    ['waktu' => '20:00', 'nilai' => 580],
                    ['waktu' => '22:00', 'nilai' => 480],
                    ['waktu' => '00:00', 'nilai' => 550],
                ],
            ],
            [
                'id' => 3,
                'nama' => 'Blok C',
                'frekuensi_irigasi' => [
                    'otomatis' => 6,
                    'manual' => 2,
                    'total' => 8,
                ],
                'kelembaban' => [
                    'rata_rata' => 54.62,
                    'min' => 38,
                    'max' => 72,
                    'status' => 'Lembab',
                ],
                'total_air_digunakan' => 480.50,
                'debit_air_rata_rata' => 115.80,
                'chart_kelembaban' => [
                    ['waktu' => '06:00', 'nilai' => 15],
                    ['waktu' => '08:00', 'nilai' => 28],
                    ['waktu' => '10:00', 'nilai' => 35],
                    ['waktu' => '12:00', 'nilai' => 45],
                    ['waktu' => '14:00', 'nilai' => 52],
                    ['waktu' => '16:00', 'nilai' => 58],
                    ['waktu' => '18:00', 'nilai' => 65],
                    ['waktu' => '20:00', 'nilai' => 60],
                    ['waktu' => '22:00', 'nilai' => 72],
                    ['waktu' => '00:00', 'nilai' => 62],
                ],
                'chart_penggunaan_air' => [
                    ['waktu' => '06:00', 'nilai' => 110],
                    ['waktu' => '08:00', 'nilai' => 200],
                    ['waktu' => '10:00', 'nilai' => 260],
                    ['waktu' => '12:00', 'nilai' => 310],
                    ['waktu' => '14:00', 'nilai' => 370],
                    ['waktu' => '16:00', 'nilai' => 440],
                    ['waktu' => '18:00', 'nilai' => 510],
                    ['waktu' => '20:00', 'nilai' => 560],
                    ['waktu' => '22:00', 'nilai' => 470],
                    ['waktu' => '00:00', 'nilai' => 530],
                ],
            ],
        ],
    ],
    
    '7days' => [
        'summary' => [
            'total_penggunaan_air' => 4538.75,
            'kelembaban_rata_rata' => 59.18,
        ],
        'bloks' => [
            [
                'id' => 1,
                'nama' => 'Blok A',
                'frekuensi_irigasi' => [
                    'otomatis' => 18,
                    'manual' => 4,
                    'total' => 22,
                ],
                'kelembaban' => [
                    'rata_rata' => 57.85,
                    'min' => 38,
                    'max' => 72,
                    'status' => 'Lembab',
                ],
                'total_air_digunakan' => 1512.25,
                'debit_air_rata_rata' => 71.25,
                'chart_kelembaban' => [
                    ['waktu' => '21 Jan', 'nilai' => 54],
                    ['waktu' => '22 Jan', 'nilai' => 58],
                    ['waktu' => '23 Jan', 'nilai' => 56],
                    ['waktu' => '24 Jan', 'nilai' => 60],
                    ['waktu' => '25 Jan', 'nilai' => 59],
                    ['waktu' => '26 Jan', 'nilai' => 57],
                    ['waktu' => '27 Jan', 'nilai' => 56],
                ],
                'chart_penggunaan_air' => [
                    ['waktu' => '21 Jan', 'nilai' => 210.50],
                    ['waktu' => '22 Jan', 'nilai' => 224.00],
                    ['waktu' => '23 Jan', 'nilai' => 218.75],
                    ['waktu' => '24 Jan', 'nilai' => 216.00],
                    ['waktu' => '25 Jan', 'nilai' => 220.50],
                    ['waktu' => '26 Jan', 'nilai' => 206.00],
                    ['waktu' => '27 Jan', 'nilai' => 216.50],
                ],
            ],
            [
                'id' => 2,
                'nama' => 'Blok B',
                'frekuensi_irigasi' => [
                    'otomatis' => 16,
                    'manual' => 6,
                    'total' => 22,
                ],
                'kelembaban' => [
                    'rata_rata' => 60.52,
                    'min' => 42,
                    'max' => 75,
                    'status' => 'Lembab',
                ],
                'total_air_digunakan' => 1568.00,
                'debit_air_rata_rata' => 73.82,
                'chart_kelembaban' => [
                    ['waktu' => '21 Jan', 'nilai' => 59],
                    ['waktu' => '22 Jan', 'nilai' => 62],
                    ['waktu' => '23 Jan', 'nilai' => 60],
                    ['waktu' => '24 Jan', 'nilai' => 61],
                    ['waktu' => '25 Jan', 'nilai' => 62],
                    ['waktu' => '26 Jan', 'nilai' => 59],
                    ['waktu' => '27 Jan', 'nilai' => 61],
                ],
                'chart_penggunaan_air' => [
                    ['waktu' => '21 Jan', 'nilai' => 228.00],
                    ['waktu' => '22 Jan', 'nilai' => 224.00],
                    ['waktu' => '23 Jan', 'nilai' => 220.00],
                    ['waktu' => '24 Jan', 'nilai' => 232.00],
                    ['waktu' => '25 Jan', 'nilai' => 224.00],
                    ['waktu' => '26 Jan', 'nilai' => 216.00],
                    ['waktu' => '27 Jan', 'nilai' => 224.00],
                ],
            ],
            [
                'id' => 3,
                'nama' => 'Blok C',
                'frekuensi_irigasi' => [
                    'otomatis' => 20,
                    'manual' => 2,
                    'total' => 22,
                ],
                'kelembaban' => [
                    'rata_rata' => 59.18,
                    'min' => 35,
                    'max' => 73,
                    'status' => 'Lembab',
                ],
                'total_air_digunakan' => 1458.50,
                'debit_air_rata_rata' => 68.65,
                'chart_kelembaban' => [
                    ['waktu' => '21 Jan', 'nilai' => 58],
                    ['waktu' => '22 Jan', 'nilai' => 60],
                    ['waktu' => '23 Jan', 'nilai' => 59],
                    ['waktu' => '24 Jan', 'nilai' => 61],
                    ['waktu' => '25 Jan', 'nilai' => 60],
                    ['waktu' => '26 Jan', 'nilai' => 58],
                    ['waktu' => '27 Jan', 'nilai' => 58],
                ],
                'chart_penggunaan_air' => [
                    ['waktu' => '21 Jan', 'nilai' => 208.50],
                    ['waktu' => '22 Jan', 'nilai' => 210.00],
                    ['waktu' => '23 Jan', 'nilai' => 206.00],
                    ['waktu' => '24 Jan', 'nilai' => 212.00],
                    ['waktu' => '25 Jan', 'nilai' => 208.00],
                    ['waktu' => '26 Jan', 'nilai' => 206.00],
                    ['waktu' => '27 Jan', 'nilai' => 208.00],
                ],
            ],
        ],
    ],
    
    '30days' => [
        'summary' => [
            'total_penggunaan_air' => 19452.30,
            'kelembaban_rata_rata' => 58.95,
        ],
        'bloks' => [
            [
                'id' => 1,
                'nama' => 'Blok A',
                'frekuensi_irigasi' => [
                    'otomatis' => 72,
                    'manual' => 18,
                    'total' => 90,
                ],
                'kelembaban' => [
                    'rata_rata' => 57.42,
                    'min' => 32,
                    'max' => 78,
                    'status' => 'Lembab',
                ],
                'total_air_digunakan' => 6480.80,
                'debit_air_rata_rata' => 72.01,
                'chart_kelembaban' => [
                    ['waktu' => 'Minggu 1', 'nilai' => 56],
                    ['waktu' => 'Minggu 2', 'nilai' => 58],
                    ['waktu' => 'Minggu 3', 'nilai' => 57],
                    ['waktu' => 'Minggu 4', 'nilai' => 58],
                ],
                'chart_penggunaan_air' => [
                    ['waktu' => 'Minggu 1', 'nilai' => 1620.20],
                    ['waktu' => 'Minggu 2', 'nilai' => 1624.60],
                    ['waktu' => 'Minggu 3', 'nilai' => 1612.00],
                    ['waktu' => 'Minggu 4', 'nilai' => 1624.00],
                ],
            ],
            [
                'id' => 2,
                'nama' => 'Blok B',
                'frekuensi_irigasi' => [
                    'otomatis' => 68,
                    'manual' => 22,
                    'total' => 90,
                ],
                'kelembaban' => [
                    'rata_rata' => 60.48,
                    'min' => 38,
                    'max' => 80,
                    'status' => 'Lembab',
                ],
                'total_air_digunakan' => 6724.50,
                'debit_air_rata_rata' => 74.72,
                'chart_kelembaban' => [
                    ['waktu' => 'Minggu 1', 'nilai' => 60],
                    ['waktu' => 'Minggu 2', 'nilai' => 61],
                    ['waktu' => 'Minggu 3', 'nilai' => 60],
                    ['waktu' => 'Minggu 4', 'nilai' => 61],
                ],
                'chart_penggunaan_air' => [
                    ['waktu' => 'Minggu 1', 'nilai' => 1681.00],
                    ['waktu' => 'Minggu 2', 'nilai' => 1688.50],
                    ['waktu' => 'Minggu 3', 'nilai' => 1672.00],
                    ['waktu' => 'Minggu 4', 'nilai' => 1683.00],
                ],
            ],
            [
                'id' => 3,
                'nama' => 'Blok C',
                'frekuensi_irigasi' => [
                    'otomatis' => 82,
                    'manual' => 8,
                    'total' => 90,
                ],
                'kelembaban' => [
                    'rata_rata' => 58.95,
                    'min' => 30,
                    'max' => 76,
                    'status' => 'Lembab',
                ],
                'total_air_digunakan' => 6247.00,
                'debit_air_rata_rata' => 69.41,
                'chart_kelembaban' => [
                    ['waktu' => 'Minggu 1', 'nilai' => 58],
                    ['waktu' => 'Minggu 2', 'nilai' => 60],
                    ['waktu' => 'Minggu 3', 'nilai' => 59],
                    ['waktu' => 'Minggu 4', 'nilai' => 59],
                ],
                'chart_penggunaan_air' => [
                    ['waktu' => 'Minggu 1', 'nilai' => 1561.75],
                    ['waktu' => 'Minggu 2', 'nilai' => 1568.25],
                    ['waktu' => 'Minggu 3', 'nilai' => 1556.00],
                    ['waktu' => 'Minggu 4', 'nilai' => 1561.00],
                ],
            ],
        ],
    ],
];
