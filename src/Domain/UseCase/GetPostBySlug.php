<?php

declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Model\PostModel;
use App\Domain\Provider\PostProviderInterface;
use App\Domain\UseCaseInterface\GetPostBySlugInterface;

readonly class GetPostBySlug implements GetPostBySlugInterface
{
    public function __construct(
        private PostProviderInterface $postProvider
    ) {
    }

    public function execute(string $slug): PostModel
    {
        return $this->postProvider->findBySlug($slug);
    }
}
