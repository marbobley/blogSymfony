<?php

declare(strict_types=1);

namespace App\Application\Model;

readonly class TagResponseModel
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $slug
    ) {
    }
}
