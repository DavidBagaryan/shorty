<?php

declare(strict_types=1);

namespace App\DTO;

use DateTimeImmutable;

class User
{
    public string $id;
    public DateTimeImmutable $createdAt;

    public function __construct(string $id, DateTimeImmutable $createdAt)
    {
        $this->id = $id;
        $this->createdAt = $createdAt;
    }

    public static function fromUserEntity(\App\Entity\User $user): self
    {
        return new self($user->getId(), $user->getCreatedAt());
    }
}
