<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Provider\PostProviderInterface;
use App\Application\UseCaseInterface\DeletePostInterface;

readonly class DeletePost implements DeletePostInterface
{
    public function __construct(
        private PostProviderInterface $postProvider
    ) {
    }

    public function execute(int $id): void
    {
        $this->postProvider->delete($id);
    }
}
