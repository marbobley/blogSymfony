<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\DTO\UserRegistrationDTO;
use App\Application\UseCaseInterface\RegisterUserInterface;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;

class RegisterUser implements RegisterUserInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function execute(UserRegistrationDTO $dto): User
    {
        // On suppose ici que le mot de passe est DEJA haché par l'infrastructure avant d'arriver au Use Case
        // ou qu'on injectera un service de hachage abstrait plus tard.
        // Pour rester simple et efficace dans un premier temps :
        $user = new User($dto->email, $dto->plainPassword);

        $this->userRepository->save($user);

        return $user;
    }
}
