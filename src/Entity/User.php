<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid", length=180, unique=true)
     */
    private string $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles;

    /**
     * @ORM\OneToMany(targetEntity=ShortUrl::class, mappedBy="user")
     */
    private Collection $shortUrls;

    /**
     * @ORM\OneToMany(targetEntity=AuthToken::class, mappedBy="user", orphanRemoval=true, cascade={"persist"})
     */
    private Collection $authTokens;

    public function __construct(string ...$roles)
    {
        $this->roles = $roles;
        $this->id = (string)Uuid::uuid6();
        $this->createdAt = new DateTimeImmutable();
        $this->shortUrls = new ArrayCollection();
        $this->authTokens = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return $this->id;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        // guarantee every user at least has ROLE_USER
        $roles = ['ROLE_USER'];
        foreach ($this->roles as $role) {
            $roles[] = strtoupper("ROLE_{$role}");
        }

        return array_unique($roles);
    }

    /**
     * @see UserInterface
     */
    public function getPassword()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|AuthToken[]
     */
    public function getAuthTokens(): Collection
    {
        return $this->authTokens;
    }

    public function addAuthToken(DateTimeImmutable $expiresAt = null): self
    {
        $this->authTokens->add(new AuthToken($this, $expiresAt));
        return $this;
    }

    /**
     * @return Collection|ShortUrl[]
     */
    public function getShortUrls(): Collection
    {
        return $this->shortUrls;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
