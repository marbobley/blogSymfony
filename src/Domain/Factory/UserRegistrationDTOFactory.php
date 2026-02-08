<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Model\UserRegistrationModel;

class UserRegistrationDTOFactory
{
    public static function create(string $email, string $plainPassword): UserRegistrationModel
    {
        return new UserRegistrationModel($email, $plainPassword);
    }
}
