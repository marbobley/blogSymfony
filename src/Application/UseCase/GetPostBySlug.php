<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Model\PostModel;
use App\Application\Provider\PostProviderInterface;
use App\Application\UseCaseInterface\GetPostBySlugInterface;

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
