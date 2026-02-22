<?php

declare(strict_types=1);

namespace App\Domain\UseCaseInterface;


interface RegisterUserInterface
{
    public function execute(string $email, string $password): void;
}
