<?php

declare(strict_types=1);

namespace App\DTO;

use DateTimeImmutable;

class CreatingDateWithUrl
{
    public DateTimeImmutable $createdAt;
    public int $count;

    public function __construct(DateTimeImmutable $createdAt, int $count)
    {
        $this->createdAt = $createdAt;
        $this->count = $count;
    }
}