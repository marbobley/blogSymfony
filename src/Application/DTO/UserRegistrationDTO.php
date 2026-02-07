<?php

declare(strict_types=1);

namespace App\Application\DTO;

final readonly class UserRegistrationDTO
{
    public function __construct(
        public string $email,
        public string $plainPassword,
    ) {
    }
}
