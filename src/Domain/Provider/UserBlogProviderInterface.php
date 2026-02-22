<?php

declare(strict_types=1);

namespace App\Domain\Provider;

use SensitiveParameter;

interface UserBlogProviderInterface
{
    function register(string $email, #[SensitiveParameter] string $plainPassword): void;
}
