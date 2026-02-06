<?php
declare(strict_types=1);
namespace App\Application\DTO;

class PostDTO
{
    public function __construct(
        public string $title,
        public string $content
    ) {
    }
}
