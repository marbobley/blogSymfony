<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Model\PostResponseModel;
use App\Application\Factory\PostResponseDTOFactory;
use App\Application\UseCaseInterface\GetPostBySlugInterface;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Repository\PostRepositoryInterface;

readonly class GetPostBySlug implements GetPostBySlugInterface
{
    public function __construct(
        private PostRepositoryInterface $postRepository
    ) {
    }

    public function execute(string $slug): PostResponseModel
    {
        $post = $this->postRepository->findBySlug($slug);

        if (!$post) {
            throw EntityNotFoundException::forEntity('Post', $slug);
        }

        return PostResponseDTOFactory::createFromEntity($post);
    }
}
