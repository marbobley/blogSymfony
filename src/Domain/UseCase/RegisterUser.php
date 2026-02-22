<?php

declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Provider\UserProviderInterface;
use App\Domain\Service\PasswordHasherInterface;
use App\Domain\UseCaseInterface\RegisterUserInterface;
use InvalidArgumentException;

readonly class RegisterUser implements RegisterUserInterface
{
    public function __construct(
        private UserProviderInterface $userRegistrationProvider,
        private PasswordHasherInterface $passwordHasher,
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
