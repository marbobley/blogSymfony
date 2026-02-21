<?php

declare(strict_types=1);

namespace App\Infrastructure\Mapper;

use App\Domain\Factory\ModelBuilderInterface;
use App\Domain\Factory\PostModelBuilder;
use App\Domain\Model\PostModel;
use App\Domain\Model\StatutArticle;
use App\Infrastructure\Entity\Post;
use App\Infrastructure\MapperInterface\PostMapperInterface;
use App\Infrastructure\MapperInterface\TagMapperInterface;
use InvalidArgumentException;

readonly class PostMapper implements PostMapperInterface
{
    public function __construct(
        private TagMapperInterface $tagMapper,
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
     * @throws InvalidArgumentException
     */
    public function toModel(Post $post): PostModel
    {
        $postBuilder = new PostModelBuilder();
        $postModel = $postBuilder
            ->setId((int) $post->getId())
            ->setTitle($post->getTitle())
            ->setContent($post->getContent())
            ->setUpdatedAt($post->getUpdatedAt())
            ->setCreatedAt($post->getCreatedAt())
            ->setSlug($post->getSlug())
            ->setSubTitle($post->getSubTitle())
            ->setPublished($post->isPublished() ? StatutArticle::PUBLISHED : StatutArticle::DRAFT)
            ->build();

        $post->isPublished() ? $postModel->publish() : $postModel->unpublish();

        $tagModels = $this->tagMapper->toModels($post->getTags());
        $postModel->addTags($tagModels);

        return $postModel;
    }
}
