<?php

declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Provider\UserBlogProviderInterface;
use App\Domain\Service\PasswordHasherInterface;
use App\Domain\UseCaseInterface\RegisterUserInterface;
use InvalidArgumentException;

readonly class RegisterUser implements RegisterUserInterface
{
    public function __construct(
        private UserBlogProviderInterface $userRegistrationProvider,
        private PasswordHasherInterface   $passwordHasher,
    ) {}

    /**
     * @throws InvalidArgumentException
     */
    public function execute(string $email, string $password): void
    {
        $hashedPassword = $this->passwordHasher->hash($password, $email);

        $this->userRegistrationProvider->register($email, $hashedPassword);
    }
}
