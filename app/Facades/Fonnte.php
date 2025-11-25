<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Notification\FonnteGateway;

/**
 * @method static void ping(string|null $recipient = null)
 * @method static void sendMessage(string $recipient, string $message, array $additional_param = [])
 *
 * @see \Modules\Notification\FonnteGateway
 *
 * @mixins \Modules\Notification\FonnteGateway
 */
class Fonnte extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return FonnteGateway::class;
    }
}
