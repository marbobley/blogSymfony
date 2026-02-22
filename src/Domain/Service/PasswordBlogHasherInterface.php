<?php

declare(strict_types=1);

namespace App\Domain\Service;

use SensitiveParameter;

interface PasswordBlogHasherInterface
{
    /**
     * @param string $plainPassword Le mot de passe en clair
     * @return string Le mot de passe haché
     */
    public function hash(string $email, #[SensitiveParameter] string $plainPassword): string;
}
