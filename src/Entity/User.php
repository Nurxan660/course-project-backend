<?php

namespace App\Entity;

use App\Enum\Role;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\Index(name: 'email_idx', columns: ['email'])]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255, unique: true)]
    private string $email;

    #[ORM\Column(length: 255)]
    private string $fullName;

    #[ORM\Column(type: 'boolean')]
    private bool $isBlocked = false;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $registerDate;

    #[ORM\Column(length: 255)]
    private string $password;

    #[ORM\Column(type: 'string', enumType: Role::class)]
    private Role $role;

    #[ORM\OneToMany(targetEntity: UserCollection::class, mappedBy: 'collection', cascade: ['remove'])]
    private Collection $collections;

    public function __construct(string $email, Role $role, string $fullName)
    {
        $this->email = $email;
        $this->role = $role;
        $this->fullName = $fullName;
        $this->registerDate = new \DateTimeImmutable();
        $this->collections = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        return [$this->role->value];
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getRegisterDate(): \DateTimeInterface
    {
        return $this->registerDate;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function isBlocked(): bool
    {
        return $this->isBlocked;
    }
}
