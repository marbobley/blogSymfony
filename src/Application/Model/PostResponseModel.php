<?php

declare(strict_types=1);

namespace App\Application\Model;

readonly class PostResponseModel
{
    public function __construct(
        public ?int $id,
        public string $title,
        public string $slug,
        public string $content,
        public \DateTimeImmutable $createdAt,
        /** @var string[] */
        public array $tags = []
    ) {
    }
}
