<?php

namespace App\Http\Controllers\Auth;

use App\Foundations\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\LoginService;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private LoginService $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.auth.login', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LoginRequest $request)
    {
        $userIp = request()->ip();
        $key = 'login-' . $userIp;

        if (RateLimiter::tooManyAttempts($key, config('auth.defaults.max_attempts'))) {
            return back()->withErrors([
                'email' => 'Too many login attempts. Please try again later.',
                'password' => 'Too many login attempts. Please try again later.',
            ]);
        }

        RateLimiter::hit($key, config('auth.defaults.throttle_seconds'));

        $user = $this->service->store($request->validated());

        if (!$user) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
                'password' => 'The provided credentials do not match our records.',
            ]);
        }

        return redirect()
            ->intended(route('home', absolute: false))
            ->with('status', 'You have successfully logged in.');
    }
}
