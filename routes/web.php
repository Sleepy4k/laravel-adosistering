<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Default route - redirect to user login
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('/login', [AuthController::class, 'userLogin'])->name('login');
Route::post('/login', [AuthController::class, 'handleUserLogin'])->name('login.submit');
Route::get('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'handleAdminLogin'])->name('admin.login.submit');
Route::get('/superadmin/login', [AuthController::class, 'superAdminLogin'])->name('superadmin.login');
Route::post('/superadmin/login', [AuthController::class, 'handleSuperAdminLogin'])->name('superadmin.login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/user/dashboard', function () {
    return view('user.dashboard');
})->name('user.dashboard');

Route::get('/user/iot-sensor/{id}', function ($id) {
    // Data dummy untuk setiap IoT Sensor
    $sensors = [
        '1' => [
            'id' => '1',
            'name' => 'IoT 1 - Kawista Emji',
            'location' => 'Dawuhan, Kab. Banyumas',
            'sensorStatus' => 'Terhubung',
            'humidity' => '47,38%',
            'flowRate' => '33 L / Menit',
            'volume' => '78 Liter',
            'pumpStatus' => 'Aktif',
            'lastUpdate' => '5 menit yang lalu',
            'isPumpOn' => true,
            'isAutoIrrigation' => false,
            'mapCenter' => [-7.5595, 109.0134],
            'mapZoom' => 15
        ],
        '2' => [
            'id' => '2',
            'name' => 'IoT 2 - Dawuhan Timur',
            'location' => 'Dawuhan, Kab. Banyumas',
            'sensorStatus' => 'Terhubung',
            'humidity' => '47,38%',
            'flowRate' => '33 L / Menit',
            'volume' => '78 Liter',
            'pumpStatus' => 'Mati',
            'lastUpdate' => '5 menit yang lalu',
            'isPumpOn' => false,
            'isAutoIrrigation' => false,
            'mapCenter' => [-7.5605, 109.0144],
            'mapZoom' => 15
        ],
        '3' => [
            'id' => '3',
            'name' => 'IoT 3 - Kawista Mernek',
            'location' => 'Dawuhan, Kab. Banyumas',
            'sensorStatus' => 'Terhubung',
            'humidity' => '47,38%',
            'flowRate' => '33 L / Menit',
            'volume' => '78 Liter',
            'pumpStatus' => 'Aktif',
            'lastUpdate' => '5 menit yang lalu',
            'isPumpOn' => true,
            'isAutoIrrigation' => false,
            'mapCenter' => [-7.5585, 109.0124],
            'mapZoom' => 15
        ],
        '4' => [
            'id' => '4',
            'name' => 'IoT 4 - Area Selatan',
            'location' => 'Dawuhan, Kab. Banyumas',
            'sensorStatus' => 'Terhubung',
            'humidity' => '45,20%',
            'flowRate' => '31 L / Menit',
            'volume' => '72 Liter',
            'pumpStatus' => 'Aktif',
            'lastUpdate' => '3 menit yang lalu',
            'isPumpOn' => true,
            'isAutoIrrigation' => true,
            'mapCenter' => [-7.5615, 109.0154],
            'mapZoom' => 15
        ],
        '5' => [
            'id' => '5',
            'name' => 'IoT 5 - Area Utara',
            'location' => 'Dawuhan, Kab. Banyumas',
            'sensorStatus' => 'Terhubung',
            'humidity' => '50,15%',
            'flowRate' => '35 L / Menit',
            'volume' => '80 Liter',
            'pumpStatus' => 'Mati',
            'lastUpdate' => '2 menit yang lalu',
            'isPumpOn' => false,
            'isAutoIrrigation' => false,
            'mapCenter' => [-7.5575, 109.0114],
            'mapZoom' => 15
        ],
    ];

    $sensor = $sensors[$id] ?? $sensors['1'];
    
    return view('user.iot-sensor-detail', ['sensor' => $sensor]);
})->name('user.iot-sensor.detail');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/superadmin/dashboard', function () {
    return view('superadmin.dashboard');
})->name('superadmin.dashboard');
