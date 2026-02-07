<?php

declare(strict_types=1);

namespace App\Application\UseCaseInterface;

use App\Application\DTO\UserRegistrationDTO;
use App\Domain\Model\User;

interface RegisterUserInterface
{
    public function execute(UserRegistrationDTO $dto): User;
}
