<?php

declare(strict_types=1);

namespace App\Storage;

use App\Entity\Message;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\SerializerInterface;
use Ramsey\Uuid\Uuid;

class JsonStorage implements StorageInterface
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var string
     */
    private $downloadDirAbsolutePath;

    /**
     * JsonStorage constructor.
     * @param string $downloadDirAbsolutePath
     * @param Filesystem $filesystem
     * @param SerializerInterface $serializer
     */
    public function __construct(
        string $downloadDirAbsolutePath,
        Filesystem $filesystem,
        SerializerInterface $serializer
    ) {
        $this->filesystem = $filesystem;
        $this->serializer = $serializer;
        $this->downloadDirAbsolutePath = $downloadDirAbsolutePath;
    }

    /**
     * @param Message $message
     */
    public function save(Message $message): void
    {
        $filename = $this->getFilename();
        $messageSerialized = $this->serializer->serialize($message, 'json', ['groups' => 'storage_message']);
        $this->filesystem->dumpFile($this->downloadDirAbsolutePath . '/' . $filename, $messageSerialized);
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        $filename = sprintf("%s.%s", Uuid::uuid4(), 'json');
        return $filename;
    }
}