<?php

namespace App\Application\DTO;

readonly class PostDTO
{
    public function __construct(
        public string $title,
        public string $content
    ) {
    }
}
