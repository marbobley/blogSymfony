<?php

declare(strict_types=1);

namespace App\Application\UseCaseInterface;

use App\Application\DTO\PostDTO;
use App\Domain\Model\Post;

interface UpdatePostInterface
{
    public function execute(int $id, PostDTO $postDTO): Post;
}
