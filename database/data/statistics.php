<?php

/*
|--------------------------------------------------------------------------
| Statistics Data - Data Statistik Sistem
|--------------------------------------------------------------------------
|
| File ini berisi data statistik ringkasan untuk dashboard.
| Digunakan untuk menampilkan angka-angka penting di halaman dashboard.
|
|--------------------------------------------------------------------------
| API ENDPOINTS (untuk Backend Developer)
|--------------------------------------------------------------------------
|
| 1. GET /api/v1/statistics
|    - Description: Mendapatkan statistik keseluruhan sistem
|    - Response: { data: Statistics }
|
| 2. GET /api/v1/statistics/users
|    - Description: Statistik pengguna
|    - Response: { 
|        data: {
|          total: number,
|          by_role: { petani: number, admin: number, superadmin: number },
|          by_status: { aktif: number, nonaktif: number }
|        }
|      }
|
| 3. GET /api/v1/statistics/irrigations
|    - Description: Statistik irigasi
|    - Parameters:
|      - period: 'today' | '7days' | '30days' (default: today)
|    - Response: {
|        data: {
|          active: number,
|          completed: number,
|          failed: number,
|          total: number
|        }
|      }
|
| 4. GET /api/v1/statistics/devices
|    - Description: Statistik perangkat IoT
|    - Response: {
|        data: {
|          total_blocks: number,
|          total_sprayers: number,
|          online_sprayers: number,
|          offline_sprayers: number
|        }
|      }
|
|--------------------------------------------------------------------------
| DATA STRUCTURE
|--------------------------------------------------------------------------
|
| Statistics:
| - total_users: number (jumlah total pengguna)
| - total_petani: number (jumlah petani)
| - total_admin: number (jumlah admin)
| - total_superadmin: number (jumlah superadmin)
| - total_blocks: number (jumlah blok)
| - total_sprayers: number (jumlah sprayer)
| - active_irrigations: number (irigasi yang sedang berjalan)
| - completed_irrigations: number (irigasi yang selesai)
| - failed_irrigations: number (irigasi yang gagal)
|
|--------------------------------------------------------------------------
| NOTE
|--------------------------------------------------------------------------
|
| Data statistik sebaiknya di-cache dan diperbarui secara periodik
| (misalnya setiap 5 menit) untuk menghindari query yang berat.
|
| Contoh implementasi dengan Laravel:
| Cache::remember('statistics', 300, function () {
|     return [
|         'total_users' => User::count(),
|         'total_petani' => User::where('role', 'petani')->count(),
|         // ... dst
|     ];
| });
|
*/

return [
    'total_users' => 6,
    'total_petani' => 4,
    'total_admin' => 1,
    'total_superadmin' => 1,
    'total_blocks' => 3,
    'total_sprayers' => 5,
    'active_irrigations' => 1,
    'completed_irrigations' => 4,
    'failed_irrigations' => 1,
];
