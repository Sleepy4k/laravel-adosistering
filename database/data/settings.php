<?php

/*
|--------------------------------------------------------------------------
| Settings Data - Pengaturan IoT
|--------------------------------------------------------------------------
|
| File ini berisi data pengaturan untuk kontrol irigasi dan safety timeout.
| Digunakan untuk halaman pengaturan user.
|
|--------------------------------------------------------------------------
| API ENDPOINTS (untuk Backend Developer)
|--------------------------------------------------------------------------
|
| 1. GET /api/v1/settings
|    - Description: Mendapatkan semua pengaturan user
|    - Response: { data: Settings }
|
| 2. PUT /api/v1/settings/kontrol-irigasi
|    - Description: Update pengaturan kontrol irigasi
|    - Request Body: {
|        kelembaban_tanah: { min: number, max: number },
|        kondisi_lahan: { kering: number, lembab: number, basah: number }
|      }
|    - Response: { success: boolean, message: string, data: KontrolIrigasi }
|
| 3. PUT /api/v1/settings/safety-timeout
|    - Description: Update pengaturan safety timeout
|    - Request Body: {
|        pengaman_irigasi: { min: number, max: number }
|      }
|    - Response: { success: boolean, message: string, data: SafetyTimeout }
|
| 4. POST /api/v1/settings/reset
|    - Description: Reset pengaturan ke default
|    - Request Body: { section: 'kontrol-irigasi'|'safety-timeout'|'all' }
|    - Response: { success: boolean, message: string, data: Settings }
|
|--------------------------------------------------------------------------
| DATA STRUCTURE
|--------------------------------------------------------------------------
|
| Settings:
| - kontrol_irigasi:
|   - kelembaban_tanah: { min: number, max: number } (persentase 0-100)
|   - kondisi_lahan: { kering: number, lembab: number, basah: number }
| - safety_timeout:
|   - pengaman_irigasi: { min: number, max: number } (dalam menit 1-10)
|
|--------------------------------------------------------------------------
| DATABASE SCHEMA SUGGESTION
|--------------------------------------------------------------------------
|
| Table: user_settings
| - id: bigint (PK)
| - user_id: bigint (FK to users)
| 
| Kontrol Irigasi columns:
| - kelembaban_min: decimal(5,2) (0-100%)
| - kelembaban_max: decimal(5,2) (0-100%)
| - kondisi_kering: decimal(5,2) (0-100%)
| - kondisi_lembab: decimal(5,2) (0-100%)
| - kondisi_basah: decimal(5,2) (0-100%)
| 
| Safety Timeout columns:
| - pengaman_min: int (1-10 menit)
| - pengaman_max: int (1-10 menit)
| 
| - created_at: timestamp
| - updated_at: timestamp
|
*/

return [
    // Pengaturan Kontrol Irigasi
    'kontrol_irigasi' => [
        'kelembaban_tanah' => [
            'min' => 20,
            'max' => 65,
            'unit' => '%',
            'description' => 'Tentukan batas nilai kelembaban tanah untuk mengatur nyala atau mati pompa secara otomatis',
            'note' => '*Pastikan Anda sudah memeriksa kondisi lahan, agar tidak terjadi kelebihan maupun kekurangan air',
        ],
        'kondisi_lahan' => [
            'kering' => 20,
            'lembab' => 50,
            'basah' => 80,
            'unit' => '%',
            'description' => 'Tentukan nilai kelembaban tanah untuk mengatur status kelembaban lahan (kering, lembab, dan basah)',
        ],
    ],

    // Pengaturan Safety Timeout
    'safety_timeout' => [
        'pengaman_irigasi' => [
            'min' => 1,
            'max' => 3,
            'unit' => 'menit',
            'description' => 'Tentukan lama waktu ketika alat tidak mengirim data digital ke sistem (menit)',
            'note' => '*fitur ini memastikan supaya irigasi yang sedang aktif dapat dimatikan otomatis ketika alat tidak mengirim data digital ke sistem. Sehingga irigasi dapat terpantau dengan maksimal',
        ],
    ],

    // Default values for reset
    'defaults' => [
        // Kontrol Irigasi
        'kelembaban_min' => 20,
        'kelembaban_max' => 65,
        'kondisi_kering' => 20,
        'kondisi_lembab' => 50,
        'kondisi_basah' => 80,
        // Safety Timeout
        'pengaman_min' => 1,
        'pengaman_max' => 3,
    ],
];
