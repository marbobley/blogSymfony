<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Security;

use App\Infrastructure\Entity\User;
use App\Infrastructure\Security\UserAdapter;
use PHPUnit\Framework\TestCase;

class UserAdapterTest extends TestCase
{
    public function testAdapter(): void
    {
        $user = $this->createMock(User::class);
        $user->method('getEmail')->willReturn('test@example.com');
        $user->method('getRoles')->willReturn(['ROLE_USER']);
        $user->method('getPassword')->willReturn('encoded_password');

        $adapter = new UserAdapter($user);

        $this->assertSame('test@example.com', $adapter->getEmail());
        $this->assertSame('test@example.com', $adapter->getUserIdentifier());
        $this->assertSame(['ROLE_USER'], $adapter->getRoles());
        $this->assertSame('encoded_password', $adapter->getPassword());
        $this->assertSame($user, $adapter->getDomainUser());
    }
}
