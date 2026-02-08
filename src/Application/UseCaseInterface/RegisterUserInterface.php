<?php

declare(strict_types=1);

namespace App\Application\UseCaseInterface;

use App\Application\Model\UserRegistrationModel;
use App\Infrastructure\Entity\User;

interface RegisterUserInterface
{
    public function execute(UserRegistrationModel $dto): User;
}
