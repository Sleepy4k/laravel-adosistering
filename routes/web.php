<?php

use App\Http\Controllers\Auth;
use App\Http\Controllers\Dashboard;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
|
| You can list public endpoint for any user in here. These routes are not guarded
| by any authentication system. In other words, any user can access it directly.
| Remember not to list anything of importance, use authenticate route instead.
*/

Route::middleware('guest')->group(function () {
    Route::resource('login', Auth\LoginController::class)
        ->only(['index', 'store'])
        ->name('index', 'login');

    Route::controller(Auth\ForgotController::class)->group(function () {
        Route::get('forgot-password', 'index')->name('password.request');
        Route::post('forgot-password', 'store')->name('password.email');
        Route::get('reset-password/{token}', 'show')->name('password.reset');
        Route::post('reset-password/{token}', 'update')->name('password.update');
    });
});


/*
|--------------------------------------------------------------------------
| Authenticated Route
|--------------------------------------------------------------------------
|
| In here you can list any route for authenticated user. These routes
| are meant to be used privately since the access is exclusive to authenticated
| user who had obtained their access through the login process.
*/

Route::middleware('auth')->group(function () {
    Route::delete('/logout', Auth\LogoutController::class)->name('logout');

    Route::get('/', Dashboard\HomeController::class)->name('home');

    Route::resource('profile', Dashboard\ProfileController::class)
        ->only(['index', 'update'])
        ->parameters(['profile' => 'profileType']);

    Route::get('/irrigation-history', Dashboard\IrrigationHistoryController::class)->name('irrigation-history');
    
    Route::get('/statistik', Dashboard\StatistikController::class)->name('user.statistik');
    
    Route::get('/notifications', Dashboard\NotificationController::class)->name('user.notifications');
    
    Route::get('/pengaturan', Dashboard\PengaturanController::class)->name('user.pengaturan');

    Route::resource('users', Dashboard\UserController::class)->except('show');
    Route::post('/users/{user}/status', [Dashboard\UserController::class, 'setActiveStatus'])->name('users.status');
});

// Default route - redirect to user login
// Route::get('/', function () {
//     return redirect()->route('login');
// })->name('home');

// Route::get('/login', [AuthController::class, 'userLogin'])->name('login');
// Route::post('/login', [AuthController::class, 'handleUserLogin'])->name('login.submit');
// Route::get('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login');
// Route::post('/admin/login', [AuthController::class, 'handleAdminLogin'])->name('admin.login.submit');
// Route::get('/superadmin/login', [AuthController::class, 'superAdminLogin'])->name('superadmin.login');
// Route::post('/superadmin/login', [AuthController::class, 'handleSuperAdminLogin'])->name('superadmin.login.submit');
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route::get('/user/profile', function () {
//     $profile = config('dummy.current_user');
//     return view('user.profile', compact('profile'));
// })->name('user.profile');

// Route::get('/user/history', function () {
//     $irrigationHistory = config('dummy.irrigation_history');
//     return view('user.irrigation-history', compact('irrigationHistory'));
// })->name('user.history');

// Route::get('/user/dashboard', function () {
//     $blocks = config('dummy.blocks');
//     return view('user.dashboard', compact('blocks'));
// })->name('user.dashboard');

// Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

// // Admin Users Management
// Route::get('/admin/users', function () {
//     $users = config('dummy.users');
//     return view('admin.users.index', compact('users'));
// })->name('admin.users.index');
// Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
// Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
// Route::get('/admin/users/{id}/edit', function ($id) {
//     // Fetch user from dummy data
//     $users = config('dummy.users');
//     $user = collect($users)->firstWhere('id', (int) $id);

//     if (!$user) {
//         // Fallback jika user tidak ditemukan
//         $user = [
//             'id' => $id,
//             'nama_pengguna' => 'User Not Found',
//             'nomor_whatsapp' => '',
//             'email' => '',
//             'jenis_pengguna' => 'Individu',
//             'nama_panggilan' => '',
//             'jenis_kelamin' => '',
//             'tanggal_lahir' => '',
//             'hp_lain' => '',
//             'pekerjaan' => '',
//             'wilayah' => '',
//             'alamat_lengkap' => '',
//             'catatan_internal' => '',
//             'username' => '',
//             'password' => '',
//             'api_key' => '',
//         ];
//     }

//     return view('admin.users.edit', compact('user'));
// })->name('admin.users.edit');
// Route::put('/admin/users/{id}', function ($id) {
//     // TODO: Implement update logic
//     return redirect()->route('admin.users.edit', $id)->with('success', 'Data pengguna berhasil diperbarui!');
// })->name('admin.users.update');
// Route::post('/admin/users/{id}/send-credential', function ($id) {
//     // TODO: Implement send credential via WhatsApp logic
//     // Data yang dikirim dari frontend:
//     // - nama: Nama pengguna
//     // - nomor_whatsapp: Nomor tujuan WA
//     // - username: Username login
//     // - password: Password login
//     // - api_key: API Key (jika ada)
//     // - domisili: Lokasi domisili
//     return redirect()->route('admin.users.edit', $id)->with('wa_sent', true);
// })->name('admin.users.send-credential');
// Route::delete('/admin/users/{id}', function ($id) {
//     // TODO: Implement delete logic
//     return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus!');
// })->name('admin.users.destroy');

// Route::get('/superadmin/dashboard', function () {
//     return view('superadmin.dashboard');
// })->name('superadmin.dashboard');

// // Super Admin Users Management
// Route::get('/superadmin/users', function () {
//     $users = config('dummy.users');
//     return view('superadmin.users.index', compact('users'));
// })->name('superadmin.users.index');
// Route::get('/superadmin/users/create', function () {
//     return view('superadmin.users.create');
// })->name('superadmin.users.create');
// Route::post('/superadmin/users', function () {
//     // TODO: Implement store logic
//     return redirect()->route('superadmin.dashboard')->with('success', 'Pengguna berhasil ditambahkan!');
// })->name('superadmin.users.store');
// Route::get('/superadmin/users/{id}/edit', function ($id) {
//     // Fetch user from dummy data
//     $users = config('dummy.users');
//     $user = collect($users)->firstWhere('id', (int) $id);

//     if (!$user) {
//         // Fallback jika user tidak ditemukan
//         $user = [
//             'id' => $id,
//             'nama_pengguna' => 'User Not Found',
//             'nomor_whatsapp' => '',
//             'email' => '',
//             'jenis_pengguna' => 'Individu',
//             'nama_panggilan' => '',
//             'jenis_kelamin' => '',
//             'tanggal_lahir' => '',
//             'hp_lain' => '',
//             'pekerjaan' => '',
//             'wilayah' => '',
//             'alamat_lengkap' => '',
//             'catatan_internal' => '',
//             'username' => '',
//             'password' => '',
//             'api_key' => '',
//         ];
//     }

//     return view('superadmin.users.edit', compact('user'));
// })->name('superadmin.users.edit');
// Route::put('/superadmin/users/{id}', function ($id) {
//     // TODO: Implement update logic
//     return redirect()->route('superadmin.users.edit', $id)->with('success', 'Data pengguna berhasil diperbarui!');
// })->name('superadmin.users.update');
// Route::post('/superadmin/users/{id}/send-credential', function ($id) {
//     // TODO: Implement send credential via WhatsApp logic
//     // Data yang dikirim dari frontend:
//     // - nama: Nama pengguna
//     // - nomor_whatsapp: Nomor tujuan WA
//     // - username: Username login
//     // - password: Password login
//     // - api_key: API Key (jika ada)
//     // - domisili: Lokasi domisili
//     return redirect()->route('superadmin.users.edit', $id)->with('wa_sent', true);
// })->name('superadmin.users.send-credential');
// Route::delete('/superadmin/users/{id}', function ($id) {
//     // TODO: Implement delete logic
//     return redirect()->route('superadmin.users.index')->with('success', 'Pengguna berhasil dihapus!');
// })->name('superadmin.users.destroy');
