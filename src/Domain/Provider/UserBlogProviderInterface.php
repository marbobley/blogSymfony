<?php

declare(strict_types=1);

namespace App\Domain\Provider;

interface UserBlogProviderInterface
{
    function register(string $email, string $plainPassword): void;
}
