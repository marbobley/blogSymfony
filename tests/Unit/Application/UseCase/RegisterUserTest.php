<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Domain\Model\UserRegistrationModel;
use App\Domain\Service\PasswordHasherInterface;
use App\Domain\UseCase\RegisterUser;
use App\Infrastructure\Entity\User;
use App\Infrastructure\Repository\UserRepositoryInterface;
use App\Tests\Unit\Helper\XmlTestDataTrait;
use PHPUnit\Framework\TestCase;

class RegisterUserTest extends TestCase
{
    use XmlTestDataTrait;

    public function testExecuteSavesAndReturnsUser(): void
    {
        // Arrange
        $users = $this->loadUserRegistrationModelsFromXml(__DIR__ . '/../../../Fixtures/users.xml');
        $dto = $users[1]; // user@example.com

        $repository = $this->createMock(UserRepositoryInterface::class);
        $passwordHasher = $this->createMock(PasswordHasherInterface::class);

        $passwordHasher->method('hash')
            ->willReturn('hashed_password');

        $repository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (User $user) use ($dto) {
                return $user->getEmail() === $dto->email &&
                       $user->getPassword() === 'hashed_password';
            }));

        $useCase = new RegisterUser($repository, $passwordHasher);

        // Act
        $result = $useCase->execute($dto);

        // Assert
        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($dto->email, $result->getEmail());
        $this->assertEquals('hashed_password', $result->getPassword());
    }

    public function testExecuteThrowsExceptionForInvalidEmail(): void
    {
        // Arrange
        $dto = new UserRegistrationModel('invalid-email', 'password');
        $repository = $this->createMock(UserRepositoryInterface::class);
        $passwordHasher = $this->createMock(PasswordHasherInterface::class);

        $useCase = new RegisterUser($repository, $passwordHasher);

        // Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('L\'adresse email est invalide.');

        // Act
        $useCase->execute($dto);
    }
}
