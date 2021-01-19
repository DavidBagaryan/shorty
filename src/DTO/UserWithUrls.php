<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Serializer\Annotation\SerializedName;

class UserWithUrls
{
    public string $user;
    public int $count;

    /**
     * @SerializedName("last_created")
     */
    public string $lastCreated;

    public function __construct(string $user, int $count, string $lastCreated)
    {
        $this->user = $user;
        $this->count = $count;
        $this->lastCreated = $lastCreated;
    }
}