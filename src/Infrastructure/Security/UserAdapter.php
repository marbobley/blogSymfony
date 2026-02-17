<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use App\Infrastructure\Entity\User as DomainUser;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class UserAdapter implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(
        private DomainUser $domainUser,
    ) {}

    /**
     * @throws \LogicException
     */
    public function getEmail(): string
    {
        return $this->domainUser->getEmail();
    }

    /**
     * @return non-empty-string
     * @throws \LogicException
     */
    public function getUserIdentifier(): string
    {
        return $this->domainUser->getEmail();
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->domainUser->getRoles();
    }

    public function getPassword(): string
    {
        return $this->domainUser->getPassword();
    }

    public function eraseCredentials(): void
    {
        // Not needed for now as we don't store plain passwords in the entity
    }

    public function getDomainUser(): DomainUser
    {
        return $this->domainUser;
    }
}
