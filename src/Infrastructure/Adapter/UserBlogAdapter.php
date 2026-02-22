<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapter;

use App\Domain\Provider\UserBlogProviderInterface;
use App\Infrastructure\Entity\User;
use App\Infrastructure\Repository\UserRepositoryInterface;

readonly class UserBlogAdapter implements UserBlogProviderInterface
{
    function __construct(private UserRepositoryInterface $userRepository){

    }

    function register(string $email, string $plainPassword): void
    {
        $user = new User($email, $plainPassword);
        $this->userRepository->save($user);
    }
}
