<?php

namespace App\Notifications\Messages;

use Illuminate\Notifications\Messages\SimpleMessage;

class WhatsappMessage extends SimpleMessage
{
    /**
     * The phone number to send the WhatsApp message to.
     *
     * @var string
     */
    public string $phoneNumber;

    /**
     * The WhatsApp message content.
     *
     * @var string
     */
    public string $message;

    /**
     * Set the phone number.
     *
     * @param  string  $phoneNumber
     * @return $this
     */
    public function phone(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    /**
     * Set the message content.
     *
     * @param  string  $message
     * @return $this
     */
    public function message(string $message): self
    {
        $this->message = $message;
        return $this;
    }
}
