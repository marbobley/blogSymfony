<?php

declare(strict_types=1);

namespace App\Infrastructure\Entity;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use LogicException;
use SensitiveParameter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use function array_unique;
use function filter_var;

#[ORM\Entity]
#[ORM\Table(name: 'user')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private string $email;

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @var string[]
     */
    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private string $password;

    public function __construct() {}

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return non-empty-string
     * @throws LogicException
     */
    public function getEmail(): string
    {
        $email = $this->email;
        if ($email === '') {
            throw new LogicException('Email cannot be empty.');
        }
        return $email;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function updateEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('L\'adresse email est invalide.');
        }

        $this->email = $email;
    }

    public function setPassword(#[SensitiveParameter] string $password): void
    {
        $this->password = $password;
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function __toString(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
        //not used
    }

    /**
     * @return string
     * @throws LogicException
     */
    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }
}
