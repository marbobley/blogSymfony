<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use App\Domain\Service\PasswordHasherInterface;
use App\Infrastructure\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SymfonyPasswordHasher implements PasswordHasherInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function hash(string $plainPassword, string $email): string
    {
        // On utilise l'UserAdapter car Symfony s'attend à un UserInterface pour hacher
        // On crée une instance temporaire d'User pour le hachage
        $user = new User($email, '');
        $adapter = new UserAdapter($user);

        return $this->passwordHasher->hashPassword($adapter, $plainPassword);
    }
}
