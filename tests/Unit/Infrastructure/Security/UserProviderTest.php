<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Security;

use App\Infrastructure\Entity\User;
use App\Infrastructure\MapperInterface\UserMapperInterface;
use App\Infrastructure\Repository\UserRepositoryInterface;
use App\Infrastructure\Security\UserAdapter;
use App\Infrastructure\Security\UserProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProviderTest extends TestCase
{
    private UserRepositoryInterface $userRepository;
    private UserMapperInterface $userMapper;
    private UserProvider $provider;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->userMapper = $this->createMock(UserMapperInterface::class);
        $this->provider = new UserProvider($this->userRepository, $this->userMapper);
    }

    public function testLoadUserByIdentifierReturnsUserAdapter(): void
    {
        $email = 'test@example.com';
        $user = $this->createMock(User::class);
        $adapter = new UserAdapter($user);

        $this->userRepository->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn($user);

        $this->userMapper->expects($this->once())
            ->method('toAdapter')
            ->with($user)
            ->willReturn($adapter);

        $result = $this->provider->loadUserByIdentifier($email);

        $this->assertInstanceOf(UserAdapter::class, $result);
        $this->assertSame($adapter, $result);
    }

    public function testLoadUserByIdentifierThrowsExceptionIfNotFound(): void
    {
        $this->userRepository->method('findByEmail')->willReturn(null);

        $this->expectException(UserNotFoundException::class);
        $this->provider->loadUserByIdentifier('nonexistent@example.com');
    }

    public function testRefreshUser(): void
    {
        $email = 'test@example.com';
        $user = $this->createMock(User::class);
        $user->method('getEmail')->willReturn($email);

        $adapter = new UserAdapter($user);

        $this->userRepository->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn($user);

        $this->userMapper->expects($this->once())
            ->method('toAdapter')
            ->with($user)
            ->willReturn($adapter);

        $result = $this->provider->refreshUser($adapter);

        $this->assertInstanceOf(UserAdapter::class, $result);
        $this->assertSame($adapter, $result);
    }

    public function testRefreshUserThrowsUnsupportedUserException(): void
    {
        $user = $this->createMock(UserInterface::class);

        $this->expectException(UnsupportedUserException::class);
        $this->provider->refreshUser($user);
    }

    public function testSupportsClass(): void
    {
        $this->assertTrue($this->provider->supportsClass(UserAdapter::class));
        $this->assertFalse($this->provider->supportsClass(User::class));
    }

    public function testUpgradePassword(): void
    {
        $user = $this->createMock(User::class);
        $adapter = new UserAdapter($user);
        $newHashedPassword = 'new_hashed_password';

        $user->expects($this->once())
            ->method('updatePassword')
            ->with($newHashedPassword);

        $this->userRepository->expects($this->once())
            ->method('save')
            ->with($user);

        $this->provider->upgradePassword($adapter, $newHashedPassword);
    }
}
