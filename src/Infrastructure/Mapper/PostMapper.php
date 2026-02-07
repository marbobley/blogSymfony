<?php

namespace App\Infrastructure\Mapper;

use App\Application\Model\PostModel;
use App\Domain\Model\Post;
use App\Infrastructure\MapperInterface\PostMapperInterface;
use App\Infrastructure\MapperInterface\TagMapperInterface;

class PostMapper implements PostMapperInterface
{
    public function __construct(private readonly TagMapperInterface $tagMapper){

    }

    public function toEntity(PostModel $postDTO): Post
    {
        $post = new Post($postDTO->getTitle(),$postDTO->getContent());
        $tags = $this->tagMapper->toEntities($postDTO->getTags());
        $post->setTags($tags);
        return $post;
    }

    public function toModel(Post $post): PostModel
    {
        $postModel = new PostModel();
        $postModel->setTitle($post->getTitle());
        $postModel->setContent($post->getContent());
        $postModel->setId($post->getId());

        $tagModels = $this->tagMapper->toModels($post->getTags());
        $postModel->addTags($tagModels);

        return $postModel;
    }
}
