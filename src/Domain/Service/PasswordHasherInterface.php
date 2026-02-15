<?php

declare(strict_types=1);

namespace App\Domain\Service;

interface PasswordHasherInterface
{
    /**
     * @param string $plainPassword Le mot de passe en clair
     * @param string $email L'email (peut être utilisé comme sel ou pour identifier le type d'utilisateur)
     * @return string Le mot de passe haché
     */
    public function hash(string $plainPassword, string $email): string;
}
