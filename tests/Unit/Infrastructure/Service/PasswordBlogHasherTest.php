<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Service;

use App\Infrastructure\Entity\User;
use App\Infrastructure\Service\PasswordBlogHasher;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordBlogHasherTest extends TestCase
{
    private UserPasswordHasherInterface $passwordHasher;
    private PasswordBlogHasher $hasher;

    protected function setUp(): void
    {
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $this->hasher = new PasswordBlogHasher($this->passwordHasher);
    }

    public function testHashCallsSymfonyHasher(): void
    {
        $email = 'user@example.com';
        $plainPassword = 'plain-password';
        $hashedPassword = 'hashed-password';

        $this->passwordHasher->expects($this->once())
            ->method('hashPassword')
            ->with($this->isInstanceOf(User::class), $plainPassword)
            ->willReturn($hashedPassword);

        $result = $this->hasher->hash($email, $plainPassword);

        $this->assertSame($hashedPassword, $result);
    }
}
