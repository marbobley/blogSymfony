<?php

declare(strict_types=1);

namespace App\Application\Factory;

use App\Application\DTO\UserRegistrationDTO;

class UserRegistrationDTOFactory
{
    public static function create(string $email, string $plainPassword): UserRegistrationDTO
    {
        return new UserRegistrationDTO($email, $plainPassword);
    }
}
