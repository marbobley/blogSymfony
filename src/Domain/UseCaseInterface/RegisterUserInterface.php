<?php

declare(strict_types=1);

namespace App\Domain\UseCaseInterface;

use SensitiveParameter;

interface RegisterUserInterface
{
    public function execute(string $email, #[SensitiveParameter] string $password): void;
}
