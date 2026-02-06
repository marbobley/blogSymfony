<?php

namespace App\Application\DTO;

readonly class PostResponseDTO
{
    public function __construct(
        public ?int $id,
        public string $title,
        public string $content,
        public \DateTimeImmutable $createdAt
    ) {
    }
}
