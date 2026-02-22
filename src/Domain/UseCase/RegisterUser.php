<?php

declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Provider\UserBlogProviderInterface;
use App\Domain\Service\PasswordBlogHasherInterface;
use App\Domain\UseCaseInterface\RegisterUserInterface;
use InvalidArgumentException;
use SensitiveParameter;

readonly class RegisterUser implements RegisterUserInterface
{
    public function __construct(
        private UserBlogProviderInterface $userRegistrationProvider,
        private PasswordBlogHasherInterface $passwordHasher,
    ) {}

    /**
     * @throws InvalidArgumentException
     */
    public function execute(string $email, #[SensitiveParameter] string $password): void
    {
        $hashedPassword = $this->passwordHasher->hash($password);
        $this->userRegistrationProvider->register($email, $hashedPassword);
    }
}
