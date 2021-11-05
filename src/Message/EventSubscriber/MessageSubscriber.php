<?php

declare(strict_types=1);

namespace App\Message\EventSubscriber;

use App\Message\Event\MessageEvent;
use App\Storage\StorageInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MessageSubscriber implements EventSubscriberInterface
{
    /**
     * @var JsonStorage
     */
    private $storage;

    /**
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            MessageEvent::class => 'onMessageCreate'
        ];
    }

    /**
     * @param MessageEvent $event
     */
    public function onMessageCreate(MessageEvent $event)
    {
        $message = $event->getMessage();
        $this->storage->save($message);
    }
}
