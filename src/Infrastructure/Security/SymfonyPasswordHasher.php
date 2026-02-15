<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use App\Domain\Service\PasswordHasherInterface;
use App\Infrastructure\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class SymfonyPasswordHasher implements PasswordHasherInterface
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function hash(string $plainPassword, string $email): string
    {
        $user = new User($email, '');
        $adapter = new UserAdapter($user);

        return $this->passwordHasher->hashPassword($adapter, $plainPassword);
    }
}
