<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Domain\Service\PasswordBlogHasherInterface;
use SensitiveParameter;
use Symfony\Component\PasswordHasher\Exception\InvalidPasswordException;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

readonly class PasswordBlogHasher implements PasswordBlogHasherInterface
{
    public function __construct(
        private PasswordHasherInterface $passwordHasher,
    ) {}

    /**
     * @param string $plainPassword
     * @return string
     * @throws InvalidPasswordException
     */
    public function hash(#[SensitiveParameter] string $plainPassword): string
    {
        return $this->passwordHasher->hash($plainPassword);
    }
}
