<?php

namespace App\Listeners;

use App\Enums\ActivityEventType;
use hisorange\BrowserDetect\Parser as Browser;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;

class UserEventListener
{
    /**
     * The IP address of the user.
     *
     * @var string
     */
    protected string $ipAddress;

    /**
     * The user agent of the request.
     *
     * @var string
     */
    protected string $userAgent;

    /**
     * Create a new event instance.
     */
    public function __construct(Request $request)
    {
        $this->ipAddress = $request->ip() ?? '';
        $this->userAgent = $request->userAgent() ?? '';
    }

    /**
     * Get the user properties for logging.
     *
     * @param  mixed  $user
     * @param  array  $extra
     * @return array
     */
    protected function getUserProperties($user, array $extra = []): array
    {
        if (Browser::isMobile()) {
            $extra['device_family'] = Browser::deviceFamily() ?? 'unknown';
            $extra['device_model'] = Browser::deviceModel() ?? 'unknown';
        } else {
            $extra['device_family'] = Browser::platformFamily() ?? 'unknown';
            $extra['device_model'] = Browser::platformName() ?? 'unknown';
        }

        return array_merge([
            'email' => $user?->email ?? '',
            'email_verified_at' => $user?->email_verified_at
                ? date('d F Y H:i:s', strtotime($user->email_verified_at))
                : null,
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
            'device_type' => Browser::deviceType() ?? 'unknown',
            'browser_family' => Browser::browserFamily() ?? 'unknown',
            'browser_version' => Browser::browserVersion() ?? 'unknown',
        ], $extra);
    }

    /**
     * Handle user login event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handleUserLogin(Login $event): void
    {
        $user = $event->user;
        if (!$user) return;

        $properties = $this->getUserProperties($user, [
            'login_at' => now()->toDateTimeString(),
        ]);

        activity('auth')
            ->event(ActivityEventType::LOGIN->value)
            ->causedBy($user?->id ?? 1)
            ->withProperties($properties)
            ->log("User {$properties['email']} successfully logged in");
    }

    /**
     * Handle user logout event.
     *
     * @param  Logout  $event
     * @return void
     */
    public function handleUserLogout(Logout $event): void
    {
        $user = $event->user;
        if (!$user) return;

        $properties = $this->getUserProperties($user, [
            'logout_at' => now()->toDateTimeString(),
        ]);

        activity('auth')
            ->event(ActivityEventType::LOGOUT->value)
            ->causedBy($user->id ?? 1)
            ->withProperties($properties)
            ->log("User {$properties['email']} successfully logged out");
    }

    /**
     * Handle user registration event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handleUserRegistration(Registered $event): void
    {
        $user = $event->user;
        if (!$user) return;

        $properties = $this->getUserProperties($user, [
            'registered_at' => now()->toDateTimeString(),
        ]);

        activity('auth')
            ->event(ActivityEventType::REGISTER->value)
            ->causedBy($user->id)
            ->withProperties($properties)
            ->log("User {$properties['email']} successfully registered");
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            Login::class => 'handleUserLogin',
            Logout::class => 'handleUserLogout',
            Registered::class => 'handleUserRegistration',
        ];
    }
}
