<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapter;

use App\Domain\Provider\UserBlogProviderInterface;
use App\Infrastructure\Entity\User;
use App\Infrastructure\Repository\UserRepositoryInterface;
use InvalidArgumentException;
use SensitiveParameter;

readonly class UserBlogAdapter implements UserBlogProviderInterface
{
    function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    /**
     * @param string $email
     * @param string $plainPassword
     * @return void
     * @throws InvalidArgumentException
     */
    function register(string $email, #[SensitiveParameter] string $plainPassword): void
    {
        $user = new User($email, $plainPassword);
        $this->userRepository->save($user);
    }
}
