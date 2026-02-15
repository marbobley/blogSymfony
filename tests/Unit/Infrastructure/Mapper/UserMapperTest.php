<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Mapper;

use App\Infrastructure\Entity\User;
use App\Infrastructure\Mapper\UserMapper;
use App\Infrastructure\Security\UserAdapter;
use PHPUnit\Framework\TestCase;

class UserMapperTest extends TestCase
{
    public function testToAdapter(): void
    {
        $user = $this->createMock(User::class);
        $mapper = new UserMapper();

        $adapter = $mapper->toAdapter($user);

        $this->assertInstanceOf(UserAdapter::class, $adapter);
        $this->assertSame($user, $adapter->getDomainUser());
    }
}
