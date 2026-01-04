<?php

/**
 * Data Dummy Statistik
 * 
 * Struktur data ini akan digunakan untuk handover ke backend.
 * Backend perlu menyediakan endpoint yang mengembalikan data dengan struktur serupa.
 * 
 * API Endpoint yang dibutuhkan:
 * GET /api/user/statistik?period={today|7days|30days}
 * 
 * Response Structure:
 * {
 *   "summary": {
 *     "total_penggunaan_air": float (Liter),
 *     "kelembaban_rata_rata": float (%)
 *   },
 *   "bloks": [
 *     {
 *       "id": int,
 *       "nama": string,
 *       "frekuensi_irigasi": {
 *         "otomatis": int,
 *         "manual": int
 *       },
 *       "kelembaban_rata_rata": float,
 *       "total_air_digunakan": float,
 *       "debit_air_rata_rata": float,
 *       "chart_kelembaban": [
 *         { "waktu": "HH:mm", "nilai": float }
 *       ],
 *       "chart_penggunaan_air": [
 *         { "waktu": "HH:mm", "nilai": float }
 *       ]
 *     }
 *   ]
 * }
 */

return [
    // Data untuk periode "Hari Ini"
    'today' => [
        'summary' => [
            'total_penggunaan_air' => 1162.53,
            'kelembaban_rata_rata' => 51.63,
        ],
        'bloks' => [
            [
                'id' => 1,
                'nama' => 'Blok A',
                'frekuensi_irigasi' => [
                    'total' => 8,
                    'otomatis' => 4,
                    'manual' => 4,
                ],
                'kelembaban' => [
                    'rata_rata' => 47.83,
                    'min' => 31,
                    'max' => 52,
                    'status' => 'Lembab', // Kering, Lembab, Basah
                ],
                'kelembaban_rata_rata' => 49.53,
                'total_air_digunakan' => 196.51,
                'debit_air_rata_rata' => 119.37,
                'chart_kelembaban' => [
                    ['waktu' => '06:00', 'nilai' => 15],
                    ['waktu' => '08:00', 'nilai' => 28],
                    ['waktu' => '10:00', 'nilai' => 35],
                    ['waktu' => '12:00', 'nilai' => 52],
                    ['waktu' => '14:00', 'nilai' => 48],
                    ['waktu' => '16:00', 'nilai' => 62],
                    ['waktu' => '18:00', 'nilai' => 58],
                    ['waktu' => '20:00', 'nilai' => 55],
                    ['waktu' => '22:00', 'nilai' => 60],
                    ['waktu' => '00:00', 'nilai' => 52],
                ],
                'chart_penggunaan_air' => [
                    ['waktu' => '06:00', 'nilai' => 80],
                    ['waktu' => '08:00', 'nilai' => 150],
                    ['waktu' => '10:00', 'nilai' => 220],
                    ['waktu' => '12:00', 'nilai' => 280],
                    ['waktu' => '14:00', 'nilai' => 350],
                    ['waktu' => '16:00', 'nilai' => 420],
                    ['waktu' => '18:00', 'nilai' => 380],
                    ['waktu' => '20:00', 'nilai' => 450],
                    ['waktu' => '22:00', 'nilai' => 480],
                    ['waktu' => '00:00', 'nilai' => 520],
                ],
            ],
            [
                'id' => 2,
                'nama' => 'Blok B',
                'frekuensi_irigasi' => [
                    'total' => 7,
                    'otomatis' => 4,
                    'manual' => 3,
                ],
                'kelembaban' => [
                    'rata_rata' => 53.72,
                    'min' => 42,
                    'max' => 65,
                    'status' => 'Lembab',
                ],
                'kelembaban_rata_rata' => 53.72,
                'total_air_digunakan' => 320.45,
                'debit_air_rata_rata' => 98.5,
                'chart_kelembaban' => [
                    ['waktu' => '06:00', 'nilai' => 45],
                    ['waktu' => '08:00', 'nilai' => 52],
                    ['waktu' => '10:00', 'nilai' => 48],
                    ['waktu' => '12:00', 'nilai' => 55],
                    ['waktu' => '14:00', 'nilai' => 60],
                    ['waktu' => '16:00', 'nilai' => 58],
                    ['waktu' => '18:00', 'nilai' => 62],
                    ['waktu' => '20:00', 'nilai' => 56],
                    ['waktu' => '22:00', 'nilai' => 50],
                    ['waktu' => '00:00', 'nilai' => 48],
                ],
                'chart_penggunaan_air' => [
                    ['waktu' => '06:00', 'nilai' => 100],
                    ['waktu' => '08:00', 'nilai' => 180],
                    ['waktu' => '10:00', 'nilai' => 250],
                    ['waktu' => '12:00', 'nilai' => 320],
                    ['waktu' => '14:00', 'nilai' => 380],
                    ['waktu' => '16:00', 'nilai' => 290],
                    ['waktu' => '18:00', 'nilai' => 350],
                    ['waktu' => '20:00', 'nilai' => 400],
                    ['waktu' => '22:00', 'nilai' => 420],
                    ['waktu' => '00:00', 'nilai' => 380],
                ],
            ],
            [
                'id' => 3,
                'nama' => 'Blok C',
                'frekuensi_irigasi' => [
                    'total' => 6,
                    'otomatis' => 5,
                    'manual' => 1,
                ],
                'kelembaban' => [
                    'rata_rata' => 47.89,
                    'min' => 35,
                    'max' => 58,
                    'status' => 'Lembab',
                ],
                'kelembaban_rata_rata' => 47.89,
                'total_air_digunakan' => 280.35,
                'debit_air_rata_rata' => 105.2,
                'chart_kelembaban' => [
                    ['waktu' => '06:00', 'nilai' => 40],
                    ['waktu' => '08:00', 'nilai' => 45],
                    ['waktu' => '10:00', 'nilai' => 50],
                    ['waktu' => '12:00', 'nilai' => 48],
                    ['waktu' => '14:00', 'nilai' => 52],
                    ['waktu' => '16:00', 'nilai' => 55],
                    ['waktu' => '18:00', 'nilai' => 50],
                    ['waktu' => '20:00', 'nilai' => 45],
                    ['waktu' => '22:00', 'nilai' => 42],
                    ['waktu' => '00:00', 'nilai' => 40],
                ],
                'chart_penggunaan_air' => [
                    ['waktu' => '06:00', 'nilai' => 90],
                    ['waktu' => '08:00', 'nilai' => 160],
                    ['waktu' => '10:00', 'nilai' => 230],
                    ['waktu' => '12:00', 'nilai' => 300],
                    ['waktu' => '14:00', 'nilai' => 280],
                    ['waktu' => '16:00', 'nilai' => 320],
                    ['waktu' => '18:00', 'nilai' => 350],
                    ['waktu' => '20:00', 'nilai' => 380],
                    ['waktu' => '22:00', 'nilai' => 360],
                    ['waktu' => '00:00', 'nilai' => 340],
                ],
            ],
            [
                'id' => 4,
                'nama' => 'Blok D',
                'frekuensi_irigasi' => [
                    'total' => 7,
                    'otomatis' => 3,
                    'manual' => 4,
                ],
                'kelembaban' => [
                    'rata_rata' => 55.12,
                    'min' => 45,
                    'max' => 68,
                    'status' => 'Lembab',
                ],
                'kelembaban_rata_rata' => 55.12,
                'total_air_digunakan' => 360.50,
                'debit_air_rata_rata' => 120.8,
                'chart_kelembaban' => [
                    ['waktu' => '06:00', 'nilai' => 50],
                    ['waktu' => '08:00', 'nilai' => 55],
                    ['waktu' => '10:00', 'nilai' => 58],
                    ['waktu' => '12:00', 'nilai' => 60],
                    ['waktu' => '14:00', 'nilai' => 55],
                    ['waktu' => '16:00', 'nilai' => 52],
                    ['waktu' => '18:00', 'nilai' => 58],
                    ['waktu' => '20:00', 'nilai' => 54],
                    ['waktu' => '22:00', 'nilai' => 50],
                    ['waktu' => '00:00', 'nilai' => 48],
                ],
                'chart_penggunaan_air' => [
                    ['waktu' => '06:00', 'nilai' => 120],
                    ['waktu' => '08:00', 'nilai' => 200],
                    ['waktu' => '10:00', 'nilai' => 280],
                    ['waktu' => '12:00', 'nilai' => 350],
                    ['waktu' => '14:00', 'nilai' => 400],
                    ['waktu' => '16:00', 'nilai' => 380],
                    ['waktu' => '18:00', 'nilai' => 420],
                    ['waktu' => '20:00', 'nilai' => 450],
                    ['waktu' => '22:00', 'nilai' => 480],
                    ['waktu' => '00:00', 'nilai' => 460],
                ],
            ],
        ],
    ],

    // Data untuk periode "7 Hari Terakhir" - dengan penurunan drastis
    '7days' => [
        'summary' => [
            'total_penggunaan_air' => 8234.67,
            'kelembaban_rata_rata' => 45.28,
        ],
        'bloks' => [
            [
                'id' => 1,
                'nama' => 'Blok A',
                'frekuensi_irigasi' => [
                    'otomatis' => 42,
                    'manual' => 14,
                ],
                'kelembaban_rata_rata' => 42.15,
                'total_air_digunakan' => 1450.80,
                'debit_air_rata_rata' => 108.3,
                'chart_kelembaban' => [
                    ['waktu' => 'Sen', 'nilai' => 65],
                    ['waktu' => 'Sel', 'nilai' => 58],
                    ['waktu' => 'Rab', 'nilai' => 45],
                    ['waktu' => 'Kam', 'nilai' => 25], // Penurunan drastis
                    ['waktu' => 'Jum', 'nilai' => 18], // Titik terendah
                    ['waktu' => 'Sab', 'nilai' => 35],
                    ['waktu' => 'Min', 'nilai' => 48],
                ],
                'chart_penggunaan_air' => [
                    ['waktu' => 'Sen', 'nilai' => 450],
                    ['waktu' => 'Sel', 'nilai' => 520],
                    ['waktu' => 'Rab', 'nilai' => 380],
                    ['waktu' => 'Kam', 'nilai' => 150], // Penurunan drastis
                    ['waktu' => 'Jum', 'nilai' => 85],  // Titik terendah
                    ['waktu' => 'Sab', 'nilai' => 280],
                    ['waktu' => 'Min', 'nilai' => 420],
                ],
            ],
            [
                'id' => 2,
                'nama' => 'Blok B',
                'frekuensi_irigasi' => [
                    'otomatis' => 35,
                    'manual' => 18,
                ],
                'kelembaban_rata_rata' => 48.92,
                'total_air_digunakan' => 2180.45,
                'debit_air_rata_rata' => 95.7,
                'chart_kelembaban' => [
                    ['waktu' => 'Sen', 'nilai' => 55],
                    ['waktu' => 'Sel', 'nilai' => 62],
                    ['waktu' => 'Rab', 'nilai' => 58],
                    ['waktu' => 'Kam', 'nilai' => 52],
                    ['waktu' => 'Jum', 'nilai' => 30], // Penurunan
                    ['waktu' => 'Sab', 'nilai' => 42],
                    ['waktu' => 'Min', 'nilai' => 50],
                ],
                'chart_penggunaan_air' => [
                    ['waktu' => 'Sen', 'nilai' => 380],
                    ['waktu' => 'Sel', 'nilai' => 450],
                    ['waktu' => 'Rab', 'nilai' => 520],
                    ['waktu' => 'Kam', 'nilai' => 480],
                    ['waktu' => 'Jum', 'nilai' => 200], // Penurunan
                    ['waktu' => 'Sab', 'nilai' => 350],
                    ['waktu' => 'Min', 'nilai' => 420],
                ],
            ],
            [
                'id' => 3,
                'nama' => 'Blok C',
                'frekuensi_irigasi' => [
                    'otomatis' => 38,
                    'manual' => 12,
                ],
                'kelembaban_rata_rata' => 44.56,
                'total_air_digunakan' => 1890.22,
                'debit_air_rata_rata' => 102.4,
                'chart_kelembaban' => [
                    ['waktu' => 'Sen', 'nilai' => 50],
                    ['waktu' => 'Sel', 'nilai' => 48],
                    ['waktu' => 'Rab', 'nilai' => 55],
                    ['waktu' => 'Kam', 'nilai' => 42],
                    ['waktu' => 'Jum', 'nilai' => 38],
                    ['waktu' => 'Sab', 'nilai' => 45],
                    ['waktu' => 'Min', 'nilai' => 52],
                ],
                'chart_penggunaan_air' => [
                    ['waktu' => 'Sen', 'nilai' => 320],
                    ['waktu' => 'Sel', 'nilai' => 400],
                    ['waktu' => 'Rab', 'nilai' => 480],
                    ['waktu' => 'Kam', 'nilai' => 350],
                    ['waktu' => 'Jum', 'nilai' => 280],
                    ['waktu' => 'Sab', 'nilai' => 380],
                    ['waktu' => 'Min', 'nilai' => 420],
                ],
            ],
            [
                'id' => 4,
                'nama' => 'Blok D',
                'frekuensi_irigasi' => [
                    'otomatis' => 28,
                    'manual' => 22,
                ],
                'kelembaban_rata_rata' => 45.48,
                'total_air_digunakan' => 2713.20,
                'debit_air_rata_rata' => 118.5,
                'chart_kelembaban' => [
                    ['waktu' => 'Sen', 'nilai' => 58],
                    ['waktu' => 'Sel', 'nilai' => 52],
                    ['waktu' => 'Rab', 'nilai' => 48],
                    ['waktu' => 'Kam', 'nilai' => 20], // Penurunan drastis
                    ['waktu' => 'Jum', 'nilai' => 35],
                    ['waktu' => 'Sab', 'nilai' => 45],
                    ['waktu' => 'Min', 'nilai' => 55],
                ],
                'chart_penggunaan_air' => [
                    ['waktu' => 'Sen', 'nilai' => 500],
                    ['waktu' => 'Sel', 'nilai' => 580],
                    ['waktu' => 'Rab', 'nilai' => 620],
                    ['waktu' => 'Kam', 'nilai' => 180], // Penurunan drastis
                    ['waktu' => 'Jum', 'nilai' => 350],
                    ['waktu' => 'Sab', 'nilai' => 480],
                    ['waktu' => 'Min', 'nilai' => 550],
                ],
            ],
        ],
    ],

    // Data untuk periode "30 Hari Terakhir" - dengan penurunan drastis
    '30days' => [
        'summary' => [
            'total_penggunaan_air' => 35678.92,
            'kelembaban_rata_rata' => 48.75,
        ],
        'bloks' => [
            [
                'id' => 1,
                'nama' => 'Blok A',
                'frekuensi_irigasi' => [
                    'otomatis' => 180,
                    'manual' => 60,
                ],
                'kelembaban_rata_rata' => 46.32,
                'total_air_digunakan' => 6250.45,
                'debit_air_rata_rata' => 112.8,
                'chart_kelembaban' => [
                    ['waktu' => 'Minggu 1', 'nilai' => 62],
                    ['waktu' => 'Minggu 2', 'nilai' => 55],
                    ['waktu' => 'Minggu 3', 'nilai' => 22], // Penurunan drastis
                    ['waktu' => 'Minggu 4', 'nilai' => 48],
                ],
                'chart_penggunaan_air' => [
                    ['waktu' => 'Minggu 1', 'nilai' => 2200],
                    ['waktu' => 'Minggu 2', 'nilai' => 1850],
                    ['waktu' => 'Minggu 3', 'nilai' => 650],  // Penurunan drastis
                    ['waktu' => 'Minggu 4', 'nilai' => 1550],
                ],
            ],
            [
                'id' => 2,
                'nama' => 'Blok B',
                'frekuensi_irigasi' => [
                    'otomatis' => 150,
                    'manual' => 75,
                ],
                'kelembaban_rata_rata' => 51.28,
                'total_air_digunakan' => 9420.30,
                'debit_air_rata_rata' => 98.5,
                'chart_kelembaban' => [
                    ['waktu' => 'Minggu 1', 'nilai' => 58],
                    ['waktu' => 'Minggu 2', 'nilai' => 52],
                    ['waktu' => 'Minggu 3', 'nilai' => 48],
                    ['waktu' => 'Minggu 4', 'nilai' => 55],
                ],
                'chart_penggunaan_air' => [
                    ['waktu' => 'Minggu 1', 'nilai' => 2800],
                    ['waktu' => 'Minggu 2', 'nilai' => 2450],
                    ['waktu' => 'Minggu 3', 'nilai' => 2100],
                    ['waktu' => 'Minggu 4', 'nilai' => 2070],
                ],
            ],
            [
                'id' => 3,
                'nama' => 'Blok C',
                'frekuensi_irigasi' => [
                    'otomatis' => 165,
                    'manual' => 55,
                ],
                'kelembaban_rata_rata' => 47.85,
                'total_air_digunakan' => 8150.67,
                'debit_air_rata_rata' => 105.2,
                'chart_kelembaban' => [
                    ['waktu' => 'Minggu 1', 'nilai' => 52],
                    ['waktu' => 'Minggu 2', 'nilai' => 15], // Penurunan drastis
                    ['waktu' => 'Minggu 3', 'nilai' => 45],
                    ['waktu' => 'Minggu 4', 'nilai' => 50],
                ],
                'chart_penggunaan_air' => [
                    ['waktu' => 'Minggu 1', 'nilai' => 2500],
                    ['waktu' => 'Minggu 2', 'nilai' => 580],  // Penurunan drastis
                    ['waktu' => 'Minggu 3', 'nilai' => 2200],
                    ['waktu' => 'Minggu 4', 'nilai' => 2870],
                ],
            ],
            [
                'id' => 4,
                'nama' => 'Blok D',
                'frekuensi_irigasi' => [
                    'otomatis' => 120,
                    'manual' => 95,
                ],
                'kelembaban_rata_rata' => 49.55,
                'total_air_digunakan' => 11857.50,
                'debit_air_rata_rata' => 122.3,
                'chart_kelembaban' => [
                    ['waktu' => 'Minggu 1', 'nilai' => 55],
                    ['waktu' => 'Minggu 2', 'nilai' => 50],
                    ['waktu' => 'Minggu 3', 'nilai' => 45],
                    ['waktu' => 'Minggu 4', 'nilai' => 52],
                ],
                'chart_penggunaan_air' => [
                    ['waktu' => 'Minggu 1', 'nilai' => 3200],
                    ['waktu' => 'Minggu 2', 'nilai' => 2950],
                    ['waktu' => 'Minggu 3', 'nilai' => 2800],
                    ['waktu' => 'Minggu 4', 'nilai' => 2907],
                ],
            ],
        ],
    ],
];
