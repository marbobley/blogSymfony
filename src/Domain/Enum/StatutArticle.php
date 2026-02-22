<?php

declare(strict_types=1);

namespace App\Domain\Enum;

enum StatutArticle: int
{
    case PUBLISHED = 1;
    case DRAFT = 2;
}
