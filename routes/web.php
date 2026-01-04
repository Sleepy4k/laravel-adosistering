<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// Default route - redirect to login
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Unified Login (untuk user, admin, dan superadmin)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'handleLogin'])->name('login.submit');
Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/user/profile', function () {
    $profile = config('dummy.current_user');
    return view('user.profile', compact('profile'));
})->name('user.profile');

Route::get('/user/history', function () {
    $irrigationHistory = config('dummy.irrigation_history');
    return view('user.irrigation-history', compact('irrigationHistory'));
})->name('user.history');

Route::get('/user/dashboard', function () {
    $blocks = config('dummy.blocks');
    return view('user.dashboard', compact('blocks'));
})->name('user.dashboard');

Route::get('/user/statistik', function () {
    $statistikData = include database_path('data/statistik.php');
    $period = request()->query('period', 'today');
    
    // Validate period
    if (!in_array($period, ['today', '7days', '30days'])) {
        $period = 'today';
    }
    
    $data = $statistikData[$period];
    
    return view('user.statistik', [
        'summary' => $data['summary'],
        'bloks' => $data['bloks'],
        'currentPeriod' => $period,
    ]);
})->name('user.statistik');

Route::get('/user/notifications', function () {
    $notifications = include database_path('data/notifications.php');
    return view('user.notifications', compact('notifications'));
})->name('user.notifications');

Route::get('/user/pengaturan', function () {
    $settings = include database_path('data/settings.php');
    return view('user.pengaturan', compact('settings'));
})->name('user.pengaturan');

Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

// Admin Users Management
Route::get('/admin/users', function () {
    $users = config('dummy.users');
    return view('admin.users.index', compact('users'));
})->name('admin.users.index');
Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
Route::get('/admin/users/{id}/edit', function ($id) {
    // Fetch user from dummy data
    $users = config('dummy.users');
    $user = collect($users)->firstWhere('id', (int) $id);
    
    if (!$user) {
        // Fallback jika user tidak ditemukan
        $user = [
            'id' => $id,
            'nama_pengguna' => 'User Not Found',
            'nomor_whatsapp' => '',
            'email' => '',
            'jenis_pengguna' => 'Individu',
            'nama_panggilan' => '',
            'jenis_kelamin' => '',
            'tanggal_lahir' => '',
            'hp_lain' => '',
            'pekerjaan' => '',
            'wilayah' => '',
            'alamat_lengkap' => '',
            'catatan_internal' => '',
            'username' => '',
            'password' => '',
            'api_key' => '',
        ];
    }
    
    return view('admin.users.edit', compact('user'));
})->name('admin.users.edit');
Route::put('/admin/users/{id}', function ($id) {
    // TODO: Implement update logic
    return redirect()->route('admin.users.edit', $id)->with('success', 'Data pengguna berhasil diperbarui!');
})->name('admin.users.update');
Route::post('/admin/users/{id}/send-credential', function ($id) {
    // TODO: Implement send credential via WhatsApp logic
    // Data yang dikirim dari frontend:
    // - nama: Nama pengguna
    // - nomor_whatsapp: Nomor tujuan WA
    // - username: Username login
    // - password: Password login
    // - api_key: API Key (jika ada)
    // - domisili: Lokasi domisili
    return redirect()->route('admin.users.edit', $id)->with('wa_sent', true);
})->name('admin.users.send-credential');
Route::delete('/admin/users/{id}', function ($id) {
    // TODO: Implement delete logic
    return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus!');
})->name('admin.users.destroy');

Route::get('/superadmin/dashboard', function () {
    return view('superadmin.dashboard');
})->name('superadmin.dashboard');

// Super Admin Users Management
Route::get('/superadmin/users', function () {
    $users = config('dummy.users');
    return view('superadmin.users.index', compact('users'));
})->name('superadmin.users.index');
Route::get('/superadmin/users/create', function () {
    return view('superadmin.users.create');
})->name('superadmin.users.create');
Route::post('/superadmin/users', function () {
    // TODO: Implement store logic
    return redirect()->route('superadmin.dashboard')->with('success', 'Pengguna berhasil ditambahkan!');
})->name('superadmin.users.store');
Route::get('/superadmin/users/{id}/edit', function ($id) {
    // Fetch user from dummy data
    $users = config('dummy.users');
    $user = collect($users)->firstWhere('id', (int) $id);
    
    if (!$user) {
        // Fallback jika user tidak ditemukan
        $user = [
            'id' => $id,
            'nama_pengguna' => 'User Not Found',
            'nomor_whatsapp' => '',
            'email' => '',
            'jenis_pengguna' => 'Individu',
            'nama_panggilan' => '',
            'jenis_kelamin' => '',
            'tanggal_lahir' => '',
            'hp_lain' => '',
            'pekerjaan' => '',
            'wilayah' => '',
            'alamat_lengkap' => '',
            'catatan_internal' => '',
            'username' => '',
            'password' => '',
            'api_key' => '',
        ];
    }
    
    return view('superadmin.users.edit', compact('user'));
})->name('superadmin.users.edit');
Route::put('/superadmin/users/{id}', function ($id) {
    // TODO: Implement update logic
    return redirect()->route('superadmin.users.edit', $id)->with('success', 'Data pengguna berhasil diperbarui!');
})->name('superadmin.users.update');
Route::post('/superadmin/users/{id}/send-credential', function ($id) {
    // TODO: Implement send credential via WhatsApp logic
    // Data yang dikirim dari frontend:
    // - nama: Nama pengguna
    // - nomor_whatsapp: Nomor tujuan WA
    // - username: Username login
    // - password: Password login
    // - api_key: API Key (jika ada)
    // - domisili: Lokasi domisili
    return redirect()->route('superadmin.users.edit', $id)->with('wa_sent', true);
})->name('superadmin.users.send-credential');
Route::delete('/superadmin/users/{id}', function ($id) {
    // TODO: Implement delete logic
    return redirect()->route('superadmin.users.index')->with('success', 'Pengguna berhasil dihapus!');
})->name('superadmin.users.destroy');
