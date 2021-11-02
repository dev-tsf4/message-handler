<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    use EntityTimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", options={"unsigned": true})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @Assert\NotBlank(message="Votre nom est requis")
     * @Assert\Length(max=100)
     * @Groups({"storage_message"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=180)
     *
     * @Assert\NotBlank(message="Veuillez renseigner votre email")
     * @Assert\Email(message="Un email valide est requis")
     * @Assert\Length(max=180)
     * @Groups({"storage_message"})
     */
    private $email;

    /**
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank(message="Veuillez saisir un message")
     * @Groups({"storage_message"})
     *
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $treated = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function isTreated(): bool
    {
        return $this->treated;
    }

    public function setTreated(bool $treated)
    {
        $this->treated = $treated;
    }
}
