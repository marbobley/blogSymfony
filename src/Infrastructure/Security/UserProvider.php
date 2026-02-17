<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use App\Infrastructure\MapperInterface\UserMapperInterface;
use App\Infrastructure\Repository\UserRepositoryInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use function is_subclass_of;
use function sprintf;

/**
 * @implements UserProviderInterface<UserAdapter>
 */
class UserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserMapperInterface $userMapper,
    ) {}

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->userRepository->findByEmail($identifier);

        if (!$user) {
            $e = new UserNotFoundException(sprintf('User with email "%s" not found.', $identifier));
            $e->setUserIdentifier($identifier);
            throw $e;
        }

        return $this->userMapper->toAdapter($user);
    }

    /**
     * @throws \LogicException
     * @throws UnsupportedUserException
     * @throws UserNotFoundException
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof UserAdapter) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return UserAdapter::class === $class || is_subclass_of($class, UserAdapter::class);
    }

    public function upgradePassword(
        PasswordAuthenticatedUserInterface $user,
        #[\SensitiveParameter] string $newHashedPassword,
    ): void {
        if (!$user instanceof UserAdapter) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $domainUser = $user->getDomainUser();
        $domainUser->updatePassword($newHashedPassword);
        $this->userRepository->save($domainUser);
    }
}
