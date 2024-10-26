<?php

namespace App\Entity;

use App\Repository\LinkRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LinkRepository::class)]
class Link
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Url]
    #[Assert\NotBlank]
    #[ORM\Column(length: 500)]
    private ?string $destinationUrl = null;

    #[ORM\Column(options: ['default' => true])]
    private ?bool $enabled = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDestinationUrl(): ?string
    {
        return $this->destinationUrl;
    }

    public function setDestinationUrl(string $destinationUrl): static
    {
        $this->destinationUrl = $destinationUrl;

        return $this;
    }

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): static
    {
        $this->enabled = $enabled;

        return $this;
    }
}
