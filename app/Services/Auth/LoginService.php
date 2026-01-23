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
        // Tidak perlu return rate limiter data - sudah di-handle di controller
        return [];
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

        // Clear rate limiter dengan key yang konsisten
        $email = strtolower($request['email']);
        $ip = request()->ip();
        $throttleKey = 'login:' . $email . '|' . $ip;
        RateLimiter::clear($throttleKey);
        
        session()->regenerate();

        return $user;
    }
}
