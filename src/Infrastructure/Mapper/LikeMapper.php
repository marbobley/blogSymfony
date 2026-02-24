<?php

declare(strict_types=1);

namespace App\Infrastructure\Mapper;

use App\Domain\Model\LikeModel;
use App\Infrastructure\Entity\Post;
use App\Infrastructure\Entity\PostLike;
use App\Infrastructure\Entity\User;
use App\Infrastructure\MapperInterface\LikeMapperInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;

readonly class LikeMapper implements LikeMapperInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    /**
     * @throws ORMException
     */
    public function toEntity(LikeModel $model): PostLike
    {
        $entity = new PostLike();

        if ($model->getPostId()) {
            $post = $this->entityManager->getReference(Post::class, $model->getPostId());
            if ($post instanceof Post) {
                $entity->setPost($post);
            }
        }

        if ($model->getUserId()) {
            $user = $this->entityManager->getReference(User::class, $model->getUserId());
            if ($user instanceof User) {
                $entity->setUser($user);
            }
        }

        $createdAt = $model->getCreatedAt();
        if ($createdAt !== null) {
            $entity->setCreatedAt($createdAt);
        }

        return $entity;
    }

    public function toModel(PostLike $entity): LikeModel
    {
        $model = new LikeModel();
        $model->setId($entity->getId());
        $model->setPostId($entity->getPost()->getId());
        $model->setUserId($entity->getUser()->getId());
        $model->setCreatedAt($entity->getCreatedAt());

        return $model;
    }
}
