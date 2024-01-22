<?php

namespace App\Entity;

use App\Repository\UserBrowserTokenRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: UserBrowserTokenRepository::class)]
#[ORM\Table(name: 'user_browser_tokens')]
class UserBrowserToken implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'browserTokens')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $endpoint = null;

    #[ORM\Column(length: 255)]
    private ?string $public_key = null;

    #[ORM\Column(length: 255)]
    private ?string $auth_token = null;


    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }

    public function setEndpoint(string $endpoint): static
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function getPublicKey(): ?string
    {
        return $this->public_key;
    }

    public function setPublicKey(string $public_key): static
    {
        $this->public_key = $public_key;

        return $this;
    }

    public function getAuthToken(): ?string
    {
        return $this->auth_token;
    }

    public function setAuthToken(string $auth_token): static
    {
        $this->auth_token = $auth_token;

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->getId(),
            'endpoint' => $this->getEndpoint(),
            'public_key' => $this->getPublicKey(),
            'auth_token' => $this->getAuthToken(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
        ];
    }
}
