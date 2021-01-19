<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\ShortUrl;
use DateTimeImmutable;
use Symfony\Component\Serializer\Annotation\SerializedName;

class Url
{
    public User $user;

    public string $hash;

    public string $original;

    /**
     * @SerializedName("created_at")
     */
    public DateTimeImmutable $createdAt;

    public function __construct(User $user, string $hash, string $original, DateTimeImmutable $createdAt)
    {
        $this->user = $user;
        $this->hash = $hash;
        $this->original = $original;
        $this->createdAt = $createdAt;
    }

    public static function fromShortUrlEntity(ShortUrl $shortUrl): self
    {
        return new self(
            User::fromUserEntity($shortUrl->getUser()),
            $shortUrl->getValue(),
            $shortUrl->getOriginal(),
            $shortUrl->getCreatedAt()
        );
    }
}
