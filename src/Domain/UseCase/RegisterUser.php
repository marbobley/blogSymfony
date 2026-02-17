<?php

declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Model\UserRegistrationModel;
use App\Domain\Service\PasswordHasherInterface;
use App\Domain\UseCaseInterface\RegisterUserInterface;
use App\Infrastructure\Entity\User;
use App\Infrastructure\Repository\UserRepositoryInterface;

readonly class RegisterUser implements RegisterUserInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasherInterface $passwordHasher,
    ) {}

    /**
     * @throws \InvalidArgumentException
     */
    public function execute(UserRegistrationModel $dto): User
    {
        $hashedPassword = $this->passwordHasher->hash($dto->plainPassword, $dto->email);

        $user = new User($dto->email, $hashedPassword);

        $this->userRepository->save($user);

        return $user;
    }
}
