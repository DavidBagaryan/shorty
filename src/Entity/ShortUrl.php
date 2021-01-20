<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ShortUrlRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use LogicException;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=ShortUrlRepository::class)
 * @ORM\Table(
 *     indexes={
 *         @ORM\Index(name="ix__short_url__user_id", columns={"user_id"}),
 *         @ORM\Index(name="ix__short_url__created_at", columns={"created_at"})
 *     },
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="ux__short_url__value__original", columns={"value", "original"})
 *     }
 * )
 */
class ShortUrl
{
    private const ALGO = 'ripemd160'; // for example

    /**
     * @ORM\Id
     * @ORM\Column(type="guid", length=180, unique=true))
     */
    private string $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="shortUrls")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private string $value;

    /**
     * @ORM\Column(type="string", length=555)
     */
    private string $original;

    public function __construct(User $user, string $original)
    {
        $this->user = $user;
        $this->original = $original;
        $this->value = $this->hashUrl();

        $this->id = (string)Uuid::uuid6();
        $this->createdAt = new DateTimeImmutable();
    }

    public static function fromString(User $user, string $original): self
    {
        $host = parse_url($original)['host'] ?? null;
        if (null === $host) {
            throw new LogicException(
                "{$original} is not an URL, please make sure 'http(s)://' prefix has been provided"
            );
        }

        return new self($user, $original);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getOriginal(): string
    {
        return $this->original;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    private function hashUrl()
    {
        if (empty($this->original)) {
            throw new LogicException('Original URL is empty');
        }

        return substr(hash(self::ALGO, $this->original), 0, 8);
    }
}
