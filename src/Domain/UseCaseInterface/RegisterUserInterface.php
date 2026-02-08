<?php

declare(strict_types=1);

namespace App\Domain\UseCaseInterface;

use App\Domain\Model\UserRegistrationModel;
use App\Infrastructure\Entity\User;

interface RegisterUserInterface
{
    public function execute(UserRegistrationModel $dto): User;
}
