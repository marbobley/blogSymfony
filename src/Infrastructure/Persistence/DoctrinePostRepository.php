<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Infrastructure\Entity\Post;
use App\Infrastructure\Repository\PostRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class DoctrinePostRepository extends AbstractDoctrineRepository implements PostRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Post::class);
    }

    public function findByTag(\App\Infrastructure\Entity\Tag $tag): array
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

    public function findPublished(?\App\Infrastructure\Entity\Tag $tag = null): array
    {
        $qb = $this->entityManager->createQueryBuilder()
            ->select('p')
            ->from(Post::class, 'p')
            ->where('p.published = :published')
            ->setParameter('published', true);

        if ($tag) {
            $qb->join('p.tags', 't')
                ->andWhere('t.id = :tagId')
                ->setParameter('tagId', $tag->getId());
        }

        return $qb->getQuery()->getResult();
    }
}
