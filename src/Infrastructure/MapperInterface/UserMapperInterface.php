<?php

declare(strict_types=1);

namespace App\Infrastructure\MapperInterface;

use App\Infrastructure\Entity\User;
use App\Infrastructure\Security\UserAdapter;

interface UserMapperInterface
{
    public function toAdapter(User $user): UserAdapter;
}
