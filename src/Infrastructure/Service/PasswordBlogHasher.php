<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Domain\Service\PasswordBlogHasherInterface;
use App\Infrastructure\Entity\User;
use InvalidArgumentException;
use SensitiveParameter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class PasswordBlogHasher implements PasswordBlogHasherInterface
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {}

    /**
     * @param string $email
     * @param string $plainPassword
     * @return string
     * @throws InvalidArgumentException
     */
    public function hash(string $email, #[SensitiveParameter] string $plainPassword): string
    {
        $user = new User($email, $plainPassword);

        return $this->passwordHasher->hashPassword($user, $plainPassword);
    }
}
