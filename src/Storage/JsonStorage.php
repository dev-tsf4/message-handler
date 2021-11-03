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
    private $uploadDirAbsolutePath;

    /**
     * JsonStorage constructor.
     * @param string $uploadDirAbsolutePath
     * @param Filesystem $filesystem
     * @param SerializerInterface $serializer
     */
    public function __construct(
        string $uploadDirAbsolutePath,
        Filesystem $filesystem,
        SerializerInterface $serializer
    ) {
        $this->filesystem = $filesystem;
        $this->serializer = $serializer;
        $this->uploadDirAbsolutePath = $uploadDirAbsolutePath;
    }

    /**
     * @param Message $message
     */
    public function save(Message $message): void
    {
        $filename = $this->getFilename();
        $messageSerialized = $this->serializer->serialize($message, 'json', ['groups' => 'storage_message']);
        $this->filesystem->dumpFile($this->uploadDirAbsolutePath . '/' . $filename, $messageSerialized);
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