<?php

/*
|--------------------------------------------------------------------------
| Blocks & Sprayers Data - Data Blok dan Sprayer IoT
|--------------------------------------------------------------------------
|
| File ini berisi data dummy untuk blok dan sprayer IoT pada dashboard.
| Digunakan untuk halaman dashboard IoT user dan admin.
|
|--------------------------------------------------------------------------
| API ENDPOINTS (untuk Backend Developer)
|--------------------------------------------------------------------------
|
| 1. GET /api/v1/blocks
|    - Description: Mendapatkan daftar semua blok dengan sprayer
|    - Parameters:
|      - user_id: Filter berdasarkan pemilik (required)
|    - Response: { data: [Block], meta: { total: number } }
|
| 2. GET /api/v1/blocks/{blockId}
|    - Description: Mendapatkan detail satu blok
|    - Response: { data: Block }
|
| 3. GET /api/v1/blocks/{blockId}/sprayers
|    - Description: Mendapatkan daftar sprayer dalam satu blok
|    - Response: { data: [Sprayer] }
|
| 4. GET /api/v1/sprayers/{sprayerId}
|    - Description: Mendapatkan detail satu sprayer
|    - Response: { data: Sprayer }
|
| 5. POST /api/v1/sprayers/{sprayerId}/pump
|    - Description: Toggle status pompa (on/off)
|    - Request Body: { action: 'on' | 'off' }
|    - Response: { success: boolean, message: string, data: Sprayer }
|
| 6. POST /api/v1/sprayers/{sprayerId}/auto-irrigation
|    - Description: Toggle mode irigasi otomatis
|    - Request Body: { enabled: boolean }
|    - Response: { success: boolean, message: string, data: Sprayer }
|
| 7. GET /api/v1/blocks/summary
|    - Description: Mendapatkan ringkasan statistik blok
|    - Response: { 
|        data: { 
|          total_blocks: number,
|          total_sprayers: number,
|          active_pumps: number,
|          avg_humidity: string
|        }
|      }
|
|--------------------------------------------------------------------------
| DATA STRUCTURE
|--------------------------------------------------------------------------
|
| Block:
| - blockId: string (A, B, C, ...)
| - blockName: string
| - avgHumidity: string (persentase rata-rata kelembaban)
| - avgFlowRate: string (debit air rata-rata)
| - totalVolume: string (total volume air)
| - sprayers: Sprayer[]
|
| Sprayer:
| - id: string
| - name: string
| - location: string (alamat lengkap)
| - sensorStatus: 'Terhubung' | 'Gangguan Sensor' | 'Offline'
| - humidity: string (persentase kelembaban)
| - flowRate: string (debit air)
| - volume: string (volume)
| - pumpStatus: 'Aktif' | 'Mati'
| - lastUpdate: string (waktu update terakhir)
| - isPumpOn: boolean
| - isAutoIrrigation: boolean
|
|--------------------------------------------------------------------------
| DATABASE SCHEMA SUGGESTION
|--------------------------------------------------------------------------
|
| Table: blocks
| - id: bigint (PK)
| - user_id: bigint (FK to users)
| - block_code: varchar(10) (A, B, C, ...)
| - name: varchar(100)
| - location_description: text
| - created_at: timestamp
| - updated_at: timestamp
|
| Table: sprayers
| - id: bigint (PK)
| - block_id: bigint (FK to blocks)
| - name: varchar(100)
| - device_id: varchar(100) (IoT device identifier)
| - is_pump_on: boolean
| - is_auto_irrigation: boolean
| - created_at: timestamp
| - updated_at: timestamp
|
| Table: sensor_readings
| - id: bigint (PK)
| - sprayer_id: bigint (FK to sprayers)
| - humidity: decimal(5,2)
| - flow_rate: decimal(8,2)
| - volume: decimal(10,2)
| - sensor_status: enum('connected', 'error', 'offline')
| - reading_at: timestamp
| - created_at: timestamp
|
| Note: Data kelembaban, debit air, volume diambil dari sensor_readings
| dengan query MAX(reading_at) per sprayer untuk mendapatkan data terkini.
|
*/

return [
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
];
