<?php

declare(strict_types=1);

namespace App\Storage;

use App\Entity\Message;

interface StorageInterface
{
    /**
     * @param Message $message
     * @return void
     */
    public function save(Message $message);
}