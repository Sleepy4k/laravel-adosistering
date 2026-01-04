<?php

namespace App\Services\Auth;

use App\Foundations\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class LoginService extends Service
{
    /**
     * Display a listing of the resource.
     */
    public function index(): array
    {
        $userIp = request()->ip();
        $key = 'login-' . $userIp;
        $rateLimiter = [
            'reset_at' => RateLimiter::availableIn($key),
            'remaining' => RateLimiter::remaining($key, config('auth.defaults.max_attempts')),
        ];

        return compact('rateLimiter');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(array $request): mixed
    {
        $payload = [
            'password' => $request['password'],
            'email' => $request['email'],
        ];

        $attempt = Auth::attempt($payload);
        if (!$attempt) return null;

        $user = auth('web')->user();

        RateLimiter::clear('login-'.request()->ip());
        session()->regenerate();

        return $user;
    }
}
