<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapter;

use App\Domain\Model\UserRegistrationModel;
use App\Domain\Provider\UserRegistrationProvider;
use App\Infrastructure\Repository\UserRepositoryInterface;

readonly class UserRegistrationAdapter implements UserRegistrationProvider
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    function register(UserRegistrationModel $user): void
    {
        // TODO: Implement register() method.
    }
}
