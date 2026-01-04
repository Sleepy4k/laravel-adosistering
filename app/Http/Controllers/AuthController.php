<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    /**
     * Rate limiting configuration
     */
    private const MAX_ATTEMPTS = 5; // Maksimal 5 kali percobaan
    private const DECAY_MINUTES = 1; // Cooldown 1 menit

    /**
     * Dummy credentials untuk semua role (dibedakan berdasarkan email)
     */
    private $credentials = [
        [
            'email' => 'user@test.com',
            'password' => 'password123',
            'user_id' => 1,
            'role' => 'user',
            'name' => 'User Test',
        ],
        [
            'email' => 'admin@test.com',
            'password' => 'password123',
            'user_id' => 2,
            'role' => 'admin',
            'name' => 'Admin Test',
        ],
        [
            'email' => 'superadmin@test.com',
            'password' => 'password123',
            'user_id' => 3,
            'role' => 'superadmin',
            'name' => 'Super Admin Test',
        ],
    ];

    /**
     * Show unified login page
     */
    public function showLogin(Request $request)
    {
        // Cek apakah masih ada countdown yang berjalan dari session
        $blockedUntil = session('blocked_until', 0);
        $now = time();
        
        if ($blockedUntil > $now) {
            // Masih dalam periode blocked - tampilkan countdown
            return view('auth.login', [
                'isBlocked' => true,
            ]);
        }
        
        // Countdown sudah selesai atau tidak ada - clear session
        session()->forget(['blocked_until', 'blocked_email']);
        
        return view('auth.login', [
            'isBlocked' => false,
        ]);
    }

    /**
     * Handle login (untuk semua role: user, admin, superadmin)
     */
    public function handleLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $throttleKey = $this->getThrottleKey($request);

        // Cek rate limiting SEBELUM proses login
        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            session(['blocked_until' => time() + $seconds]);
            
            return redirect()->route('login');
        }

        // Cari user berdasarkan email
        $user = collect($this->credentials)->first(function ($cred) use ($request) {
            return $cred['email'] === $request->email && $cred['password'] === $request->password;
        });

        if ($user) {
            // Clear rate limiting setelah login berhasil
            RateLimiter::clear($throttleKey);

            $request->session()->put('dummy_auth', [
                'user_id' => $user['user_id'],
                'role' => $user['role'],
                'name' => $user['name'],
                'email' => $user['email'],
            ]);

            // Redirect berdasarkan role
            $redirectRoute = match ($user['role']) {
                'admin' => 'admin.dashboard',
                'superadmin' => 'superadmin.dashboard',
                default => 'user.dashboard',
            };

            return redirect()->route($redirectRoute)
                ->with('success', 'Berhasil login sebagai ' . ucfirst($user['role']) . '!');
        }

        // Increment failed attempts SETELAH login gagal
        RateLimiter::hit($throttleKey, self::DECAY_MINUTES * 60);
        
        // Cek apakah SEKARANG sudah terlalu banyak attempts (tepat di percobaan ke-5)
        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            session(['blocked_until' => time() + $seconds]);
            
            return redirect()->route('login');
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->withInput();
    }

    /**
     * Get throttle key untuk rate limiting
     */
    private function getThrottleKey(Request $request): string
    {
        $email = $request->input('email', 'guest');
        $ip = $request->ip();
        
        return 'login:' . strtolower($email) . '|' . $ip;
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        $request->session()->forget('dummy_auth');
        return redirect()->route('login')->with('success', 'Berhasil logout!');
    }

    /**
     * Helper function to check if user is authenticated
     */
    public static function isDummyAuthenticated()
    {
        return session()->has('dummy_auth');
    }

    /**
     * Helper function to get authenticated user data
     */
    public static function getDummyUser()
    {
        return session('dummy_auth');
    }

    /**
     * Helper function to check user role
     */
    public static function hasRole($role)
    {
        $user = self::getDummyUser();
        return $user && $user['role'] === $role;
    }
}
