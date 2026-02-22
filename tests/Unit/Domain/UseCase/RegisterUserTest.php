<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\UseCase;

use App\Domain\Provider\UserBlogProviderInterface;
use App\Domain\Service\PasswordBlogHasherInterface;
use App\Domain\UseCase\RegisterUser;
use PHPUnit\Framework\TestCase;

class RegisterUserTest extends TestCase
{
    public function testExecuteCreatesAndSavesTag(): void
    {
        $passwordHasher = $this->createMock(PasswordBlogHasherInterface::class)  ;
        $userBlogProvider = $this->createMock(UserBlogProviderInterface::class);

        $useCase = new RegisterUser($userBlogProvider, $passwordHasher);

        $passwordHasher->expects($this->once())
            ->method('hash');

        $userBlogProvider->expects($this->once())
            ->method('register');

        $useCase->execute('email@gmail.fr', 'password');
    }
}
