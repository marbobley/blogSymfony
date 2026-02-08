<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Model\PostModel;
use App\Application\Provider\PostProviderInterface;
use App\Application\UseCaseInterface\CreatePostInterface;

readonly class CreatePost implements CreatePostInterface
{
    public function __construct(
        private PostProviderInterface $postProvider
    ) {
    }

    public function execute(PostModel $postModel): PostModel
    {
        return $this->postProvider->save($postModel);
    }
}
