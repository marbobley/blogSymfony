<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Model\Post;
use App\Domain\Repository\PostRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class DoctrinePostRepository extends AbstractDoctrineRepository implements PostRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Post::class);
    }

    public function findByTag(\App\Domain\Model\Tag $tag): array
    {
        return $this->entityManager->createQueryBuilder()
            ->select('p')
            ->from(Post::class, 'p')
            ->join('p.tags', 't')
            ->where('t.id = :tagId')
            ->setParameter('tagId', $tag->getId())
            ->getQuery()
            ->getResult();
    }
}
