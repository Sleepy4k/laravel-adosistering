<?php

namespace App\Http\Controllers\Auth;

use App\Foundations\Controller;
use App\Http\Requests\Auth\ForgotRequest;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.auth.forgot-password');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ForgotRequest $request)
    {
        $email = filter_var($request->validated()['email'], FILTER_VALIDATE_EMAIL);

        if (!$email) {
            return back()->withErrors(['email' => 'Invalid email address.']);
        }

        if (!User::where('email', $email)->exists()) {
            return back()->withErrors(['email' => 'The provided email does not exist in our records.']);
        }

        $message = [
            'type' => 'error',
            'message' => 'The provided email does not exist in our records.',
        ];

        switch (Password::sendResetLink(['email' => $email])) {
            case Password::RESET_LINK_SENT:
                $message = [
                    'type' => 'success',
                    'message' => __('passwords.sent'),
                ];
                break;
            case Password::INVALID_USER:
                $message = [
                    'type' => 'error',
                    'message' => __('passwords.user'),
                ];
                break;
            case Password::RESET_THROTTLED:
                $message = [
                    'type' => 'error',
                    'message' => __('passwords.throttled'),
                ];
                break;
            case Password::INVALID_TOKEN:
                $message = [
                    'type' => 'error',
                    'message' => __('passwords.token'),
                ];
                break;
        }

        return $message['type'] === 'success'
            ? back()->with('success', $message['message'])
            : back()->withErrors(['email' => $message['message']]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $token)
    {
        $email = $request->query('email');
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return back()->withErrors(['email' => 'Invalid email address.']);
        }

        if (!User::where('email', $email)->exists()) {
            return back()->withErrors(['email' => 'The provided email does not exist in our records.']);
        }

        if (!Password::tokenExists(User::where('email', $email)->first(), $token)) {
            return back()->withErrors(['email' => __('passwords.token')]);
        }

        return view('pages.auth.reset-password', compact('token', 'email'))
            ->with('status', 'Please enter your new password.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePasswordRequest $request, string $token)
    {
        $credentials = $request->validated();
        $status = Password::reset(
            array_merge($credentials, ['token' => $token]),
            function ($user) use ($credentials) {
                $user->password = bcrypt($credentials['password']);
                $user->save();

                event(new PasswordReset($user));
            }
        );

        switch ($status) {
            case Password::PASSWORD_RESET:
                return to_route('login')->with('status', __('passwords.reset'));
            case Password::INVALID_TOKEN:
                return back()->withErrors(['email' => __('passwords.token')]);
            case Password::INVALID_USER:
                return back()->withErrors(['email' => __('passwords.user')]);
            case Password::RESET_THROTTLED:
                return back()->withErrors(['email' => __('passwords.throttled')]);
            default:
                return back()->withErrors(['email' => __('passwords.user')]);
        }
    }
}
