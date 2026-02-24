<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapter;

use App\Domain\Model\LikeModel;
use App\Domain\Provider\LikeProviderInterface;
use App\Infrastructure\MapperInterface\LikeMapperInterface;
use App\Infrastructure\Repository\LikeRepositoryInterface;

readonly class LikeAdapter implements LikeProviderInterface
{
    public function __construct(
        private LikeRepositoryInterface $likeRepository,
        private LikeMapperInterface $likeMapper,
    ) {}

    public function save(LikeModel $like): void
    {
        $entity = $this->likeMapper->toEntity($like);
        $this->likeRepository->save($entity);
    }

    public function remove(LikeModel $like): void
    {
        $entity = $this->likeRepository->findOneByPostAndUser((int) $like->getPostId(), (int) $like->getUserId());

        if ($entity) {
            $this->likeRepository->delete($entity);
        }
    }

    public function findByPostAndUser(int $postId, int $userId): ?LikeModel
    {
        $entity = $this->likeRepository->findOneByPostAndUser($postId, $userId);

        if (!$entity) {
            return null;
        }

        return $this->likeMapper->toModel($entity);
    }

    public function countByPost(int $postId): int
    {
        return $this->likeRepository->countByPost($postId);
    }
}
