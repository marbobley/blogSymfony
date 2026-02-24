<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Infrastructure\Entity\PostLike;
use App\Infrastructure\Repository\LikeRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends AbstractDoctrineRepository<PostLike>
 */
class DoctrineLikeRepository extends AbstractDoctrineRepository implements LikeRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, PostLike::class);
    }

    public function findOneByPostAndUser(int $postId, int $userId): ?PostLike
    {
        return $this->entityManager
            ->getRepository(PostLike::class)
            ->findOneBy([
                'post' => $postId,
                'user' => $userId,
            ]);
    }

    public function countByPost(int $postId): int
    {
        return $this->entityManager->getRepository(PostLike::class)->count(['post' => $postId]);
    }
}
