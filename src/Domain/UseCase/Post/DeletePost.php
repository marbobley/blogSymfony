<?php

declare(strict_types=1);

namespace App\Domain\UseCase\Post;

use App\Domain\Provider\PostProviderInterface;
use App\Domain\UseCaseInterface\Post\DeletePostInterface;

readonly class DeletePost implements DeletePostInterface
{
    public function __construct(
        private PostProviderInterface $postProvider,
    ) {}

    public function execute(int $id): void
    {
        $this->postProvider->delete($id);
    }
}
