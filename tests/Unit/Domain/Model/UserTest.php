<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Model;

use App\Infrastructure\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testConstructorThrowsExceptionWhenEmailIsInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('L\'adresse email est invalide.');

        new User('invalid-email', 'password123');
    }

    public function testUserCreationAndGetters(): void
    {
        $user = new User('test@example.com', 'hashed_password', ['ROLE_ADMIN']);

        $this->assertEquals('test@example.com', $user->getEmail());
        $this->assertEquals('hashed_password', $user->getPassword());
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
        $this->assertContains('ROLE_USER', $user->getRoles());
    }

    public function testUpdateEmailThrowsExceptionWhenInvalid(): void
    {
        $user = new User('test@example.com', 'password');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('L\'adresse email est invalide.');

        $user->updateEmail('not-an-email');
    }

    public function testUpdateEmailSuccess(): void
    {
        $user = new User('test@example.com', 'password');
        $user->updateEmail('new@example.com');

        $this->assertEquals('new@example.com', $user->getEmail());
    }
}
