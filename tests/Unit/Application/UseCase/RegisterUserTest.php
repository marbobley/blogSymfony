<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\DTO\UserRegistrationDTO;
use App\Application\UseCase\RegisterUser;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class RegisterUserTest extends TestCase
{
    public function testExecuteSavesAndReturnsUser(): void
    {
        // Arrange
        $dto = new UserRegistrationDTO('test@example.com', 'hashed_password');

        $repository = $this->createMock(UserRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (User $user) use ($dto) {
                return $user->getEmail() === $dto->email &&
                       $user->getPassword() === $dto->plainPassword;
            }));

        $useCase = new RegisterUser($repository);

        // Act
        $result = $useCase->execute($dto);

        // Assert
        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('test@example.com', $result->getEmail());
        $this->assertEquals('hashed_password', $result->getPassword());
    }

    public function testExecuteThrowsExceptionForInvalidEmail(): void
    {
        // Arrange
        $dto = new UserRegistrationDTO('invalid-email', 'password');
        $repository = $this->createMock(UserRepositoryInterface::class);

        $useCase = new RegisterUser($repository);

        // Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('L\'adresse email est invalide.');

        // Act
        $useCase->execute($dto);
    }
}
