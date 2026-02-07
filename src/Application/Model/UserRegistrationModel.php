<?php

declare(strict_types=1);

namespace App\Application\Model;

final readonly class UserRegistrationModel
{
    public function __construct(
        public string $email,
        public string $plainPassword,
    ) {
    }
}
