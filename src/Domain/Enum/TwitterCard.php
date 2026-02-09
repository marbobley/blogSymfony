<?php

declare(strict_types=1);

namespace App\Domain\Enum;

enum TwitterCard: string
{
    case SUMMARY = 'summary';
    case SUMMARY_LARGE_IMAGE = 'summary_large_image';
    case APP = 'app';
    case PLAYER = 'player';
}
