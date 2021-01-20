<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AuthTokenRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=AuthTokenRepository::class)
 * @ORM\Table(indexes={
 *     @ORM\Index(name="ix__auth_token__user_id", columns={"user_id"})
 * })
 */
class AuthToken
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid", length=180, unique=true)
     */
    private string $key;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $expiresAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="authTokens")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    public function __construct(User $user, DateTimeImmutable $expiresAt = null)
    {
        $this->user = $user;
        $this->key = (string)Uuid::uuid6();
        $this->expiresAt = $expiresAt ?? new DateTimeImmutable('+2 weeks');
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getExpiresAt(): ?DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function isExpired(): bool
    {
        return $this->expiresAt < new DateTimeImmutable();
    }
}
