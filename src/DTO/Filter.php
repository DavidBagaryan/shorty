<?php

declare(strict_types=1);

namespace App\DTO;

use DateTimeImmutable;

/**
 * @property-read null|string $userId
 * @property-read null|DateTimeImmutable $createdAt
 */
class Filter
{
    public const USER_ID = 'user-id';
    public const CREATED_AT = 'created-at';

    public function __construct(?string $userId, ?DateTimeImmutable $createdAt)
    {
        $this->userId = $userId;
        $this->createdAt = $createdAt;
    }

    public static function fromTimestamp(?string $userId, ?string $createdAt): self
    {
        return new self(
            $userId,
            null === $createdAt || empty($createdAt)
                ? null
                : (new DateTimeImmutable())->setTimestamp((int)$createdAt)
        );
    }

    public function hasUserId(): bool
    {
        return null !== $this->userId && !empty($this->userId);
    }

    public function hasCreationDate(): bool
    {
        return null !== $this->createdAt;
    }

    public function hasOnlyCreationDate(): bool
    {
        return $this->hasCreationDate() && !$this->hasUserId();
    }
}
