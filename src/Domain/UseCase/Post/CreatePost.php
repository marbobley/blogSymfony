<?php

declare(strict_types=1);

namespace App\Domain\UseCase\Post;

use App\Domain\Model\PostModel;
use App\Domain\Provider\PostProviderInterface;
use App\Domain\UseCaseInterface\Post\CreatePostInterface;

readonly class CreatePost implements CreatePostInterface
{
    public function __construct(
        private PostProviderInterface $postProvider,
    ) {}

    public function execute(PostModel $postModel): PostModel
    {
        return $this->postProvider->save($postModel);
    }
}
