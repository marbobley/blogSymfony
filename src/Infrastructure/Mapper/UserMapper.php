<?php

declare(strict_types=1);

namespace App\Infrastructure\Mapper;

use App\Infrastructure\Entity\User;
use App\Infrastructure\MapperInterface\UserMapperInterface;
use App\Infrastructure\Security\UserAdapter;

class UserMapper implements UserMapperInterface
{
    public function toAdapter(User $user): UserAdapter
    {
        return new UserAdapter($user);
    }
}
