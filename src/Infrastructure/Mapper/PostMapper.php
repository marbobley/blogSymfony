<?php

namespace App\Infrastructure\Mapper;

use App\Domain\Model\PostModel;
use App\Infrastructure\MapperInterface\PostMapperInterface;
use App\Infrastructure\MapperInterface\TagMapperInterface;
use App\Infrastructure\Entity\Post;

class PostMapper implements PostMapperInterface
{
    public function __construct(private readonly TagMapperInterface $tagMapper){

    }

    public function toEntity(PostModel $postDTO): Post
    {

        $post = new Post($postDTO->getTitle(),$postDTO->getContent());
        $post->setSubTitle($postDTO->getSubTitle());
        $post->setPublished($postDTO->isPublished());

        $tags = $this->tagMapper->toEntities($postDTO->getTags());
        $post->setTags($tags);
        return $post;
    }

    public function toModel(Post $post): PostModel
    {
        $postModel = new PostModel();
        $postModel->setTitle($post->getTitle());
        $postModel->setContent($post->getContent());
        $postModel->setId((int)$post->getId());
        $postModel->setSlug((string)$post->getSlug());
        $postModel->setSubTitle($post->getSubTitle());
        $postModel->setCreatedAt($post->getCreatedAt());
        $postModel->setUpdatedAt($post->getUpdatedAt());
        $postModel->setPublished($post->isPublished());

        $tagModels = $this->tagMapper->toModels($post->getTags());
        $postModel->addTags($tagModels);

        return $postModel;
    }
}
