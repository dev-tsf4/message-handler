<?php

declare(strict_types=1);

namespace App\Message\Event;

use App\Entity\Message;
use Symfony\Contracts\EventDispatcher\Event;

class MessageEvent extends Event
{
    /**
     * @var Message
     */
    private $message;

    /**
     * MessageEvent constructor.
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * @return Message
     */
    public function getMessage(): Message
    {
        return $this->message;
    }
}