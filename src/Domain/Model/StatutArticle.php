<?php

declare(strict_types=1);

namespace App\Domain\Model;

enum StatutArticle : int {
    case PUBLISHED = 1;
    case DRAFT = 2;
}
