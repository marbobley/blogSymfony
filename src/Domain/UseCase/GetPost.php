<?php

declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Model\PostModel;
use App\Domain\Provider\PostProviderInterface;
use App\Domain\UseCaseInterface\GetPostInterface;

readonly class GetPost implements GetPostInterface
{
    public function __construct(
        private PostProviderInterface $postProvider,
    ) {}

    public function execute(int $id): PostModel
    {
        return $this->postProvider->findById($id);
    }
}
