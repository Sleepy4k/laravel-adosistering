<?php

namespace App\Http\Controllers\Auth;

use App\Foundations\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\LoginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /**
     * Rate limiting configuration
     */
    private const MAX_ATTEMPTS = 5;
    private const DECAY_SECONDS = 60;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private LoginService $service
    ) {}

    /**
     * Display login page
     */
    public function index(Request $request)
    {
        $blockedUntil = session('blocked_until', 0);
        $now = time();
        
        // Jika masih blocked
        if ($blockedUntil > $now) {
            return view('pages.auth.login', [
                'isBlocked' => true,
                'blockedUntil' => $blockedUntil,
            ]);
        }
        
        // Clear session jika sudah tidak blocked
        session()->forget(['blocked_until', 'blocked_email']);
        
        return view('pages.auth.login', [
            'isBlocked' => false,
            'blockedUntil' => 0,
        ]);
    }

    /**
     * Handle login attempt
     */
    public function store(LoginRequest $request)
    {
        $email = strtolower(trim($request->input('email')));
        $ip = $request->ip();
        $throttleKey = "login:{$email}|{$ip}";
        
        // Debug log
        Log::info('=== LOGIN ATTEMPT ===', [
            'email' => $email,
            'ip' => $ip,
            'key' => $throttleKey,
        ]);

        // 1. Cek apakah sedang blocked via session
        $blockedUntil = session('blocked_until', 0);
        if ($blockedUntil > time()) {
            $remaining = $blockedUntil - time();
            Log::info('User is blocked', ['remaining' => $remaining]);
            
            return back()
                ->withInput(['email' => $email])
                ->withErrors(['email' => "Akun terkunci. Coba lagi dalam {$remaining} detik."]);
        }

        // 2. Cek rate limiter SEBELUM attempt
        $currentAttempts = RateLimiter::attempts($throttleKey);
        Log::info('Current attempts before hit', ['attempts' => $currentAttempts]);
        
        if ($currentAttempts >= self::MAX_ATTEMPTS) {
            // Sudah mencapai limit - block user
            $this->blockUser($email);
            Log::info('Rate limit reached - blocking user');
            
            return $this->blockedResponse($email);
        }

        // 3. Attempt login
        $user = $this->service->store($request->validated());

        if (!$user) {
            // Login GAGAL - increment rate limiter
            RateLimiter::hit($throttleKey, self::DECAY_SECONDS);
            
            $newAttempts = RateLimiter::attempts($throttleKey);
            $remaining = self::MAX_ATTEMPTS - $newAttempts;
            
            Log::info('Login failed', [
                'attempts_after' => $newAttempts,
                'remaining' => $remaining,
            ]);
            
            // Jika sudah habis, block
            if ($remaining <= 0) {
                $this->blockUser($email);
                Log::info('Max attempts reached - blocking');
                
                return $this->blockedResponse($email);
            }
            
            // Warning muncul ketika percobaan > 3 (remaining <= 2)
            $showWarning = $remaining <= 2;
            
            // Masih ada kesempatan
            return back()
                ->withInput(['email' => $email])
                ->with('attempts_left', $remaining)
                ->with('show_warning', $showWarning)
                ->withErrors([
                    'email' => 'Email atau password salah.',
                    'password' => 'Email atau password salah.',
                ]);
        }

        // 4. Login BERHASIL - clear everything
        RateLimiter::clear($throttleKey);
        session()->forget(['blocked_until', 'blocked_email']);
        
        Log::info('Login successful - cleared rate limiter');

        return redirect()
            ->intended(route('home', absolute: false))
            ->with('status', 'Login berhasil!');
    }

    /**
     * Block user for DECAY_SECONDS
     */
    private function blockUser(string $email): void
    {
        $blockedUntil = time() + self::DECAY_SECONDS;
        
        session([
            'blocked_until' => $blockedUntil,
            'blocked_email' => $email,
        ]);
        
        // PENTING: Save session immediately
        session()->save();
        
        Log::info('User blocked until', ['blocked_until' => $blockedUntil, 'now' => time()]);
    }

    /**
     * Return blocked response
     */
    private function blockedResponse(string $email)
    {
        $blockedUntil = session('blocked_until', time() + self::DECAY_SECONDS);
        $remaining = max(0, $blockedUntil - time());
        
        return back()
            ->withInput(['email' => $email])
            ->withErrors(['email' => "Terlalu banyak percobaan. Coba lagi dalam {$remaining} detik."]);
    }
}
