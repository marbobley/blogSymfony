<?php

declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Model\UserRegistrationModel;
use App\Domain\UseCaseInterface\RegisterUserInterface;
use App\Infrastructure\Entity\User;
use App\Infrastructure\Repository\UserRepositoryInterface;

readonly class RegisterUser implements RegisterUserInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function execute(UserRegistrationModel $dto): User
    {
        // On suppose ici que le mot de passe est DEJA haché par l'infrastructure avant d'arriver au Use Case
        // ou qu'on injectera un service de hachage abstrait plus tard.
        // Pour rester simple et efficace dans un premier temps :
        $user = new User($dto->email, $dto->plainPassword);

        $this->userRepository->save($user);

        return $user;
    }
}
