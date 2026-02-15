<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use App\Domain\Service\PasswordHasherInterface;
use App\Infrastructure\Entity\User;
use App\Infrastructure\MapperInterface\UserMapperInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class SymfonyPasswordHasher implements PasswordHasherInterface
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private UserMapperInterface $userMapper
    ) {
    }

    public function hash(string $plainPassword, string $email): string
    {
        $user = new User($email, '');
        $adapter = $this->userMapper->toAdapter($user);

        return $this->passwordHasher->hashPassword($adapter, $plainPassword);
    }
}
