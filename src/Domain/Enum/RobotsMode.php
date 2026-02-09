<?php

declare(strict_types=1);

namespace App\Domain\Enum;

enum RobotsMode: string
{
    case INDEX_FOLLOW = 'index, follow';
    case NOINDEX_FOLLOW = 'noindex, follow';
    case INDEX_NOFOLLOW = 'index, nofollow';
    case NOINDEX_NOFOLLOW = 'noindex, nofollow';
}
