<?php

declare(strict_types=1);

namespace App\Infrastructure\Mapper;

use App\Domain\Model\PostModel;
use App\Infrastructure\Entity\Post;
use App\Infrastructure\MapperInterface\PostMapperInterface;
use App\Infrastructure\MapperInterface\TagMapperInterface;

class PostMapper implements PostMapperInterface
{
    public function __construct(
        private readonly TagMapperInterface $tagMapper,
    ) {}

    public function toEntity(PostModel $postDTO): Post
    {
        $post = new Post($postDTO->getTitle(), $postDTO->getContent());
        $post->setSubTitle($postDTO->getSubTitle());
        $postDTO->isPublished() ? $post->publish() : $post->unpublish();

        $tags = $this->tagMapper->toEntities($postDTO->getTags());
        $post->setTags($tags);
        return $post;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function toModel(Post $post): PostModel
    {
        $postModel = new PostModel();
        $postModel->setTitle($post->getTitle());
        $postModel->setContent($post->getContent());
        $postModel->setId((int) $post->getId());
        $postModel->setSlug((string) $post->getSlug());
        $postModel->setSubTitle($post->getSubTitle());
        $postModel->setCreatedAt($post->getCreatedAt());
        $postModel->setUpdatedAt($post->getUpdatedAt());
        $post->isPublished() ? $postModel->publish() : $postModel->unpublish();

        $tagModels = $this->tagMapper->toModels($post->getTags());
        $postModel->addTags($tagModels);

        return $postModel;
    }
}
