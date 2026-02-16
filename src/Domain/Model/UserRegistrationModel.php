<?php

declare(strict_types=1);

namespace App\Domain\Model;

class UserRegistrationModel
{
    public string $email = '';
    public string $plainPassword = '';

    public function __construct(string $email = '', #[\SensitiveParameter] string $plainPassword = '')
    {
        $this->email = $email;
        $this->plainPassword = $plainPassword;
    }
}
