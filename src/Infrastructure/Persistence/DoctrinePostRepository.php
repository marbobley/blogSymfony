<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Infrastructure\Entity\Post;
use App\Infrastructure\Repository\PostRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends AbstractDoctrineRepository<Post>
 */
class DoctrinePostRepository extends AbstractDoctrineRepository implements PostRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Post::class);
    }

    public function findAll(?string $search = null): array
    {
        $qb = $this->entityManager->createQueryBuilder()
            ->select('p')
            ->from(Post::class, 'p');

        if ($search) {
            $qb->andWhere('p.title LIKE :search OR p.content LIKE :search OR p.subTitle LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        return $qb->getQuery()->getResult();
    }

    public function findByTag(\App\Infrastructure\Entity\Tag $tag, ?string $search = null): array
    {
        $qb = $this->entityManager->createQueryBuilder()
            ->select('p')
            ->from(Post::class, 'p')
            ->join('p.tags', 't')
            ->where('t.id = :tagId')
            ->setParameter('tagId', $tag->getId());

        if ($search) {
            $qb->andWhere('p.title LIKE :search OR p.content LIKE :search OR p.subTitle LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        return $qb->getQuery()->getResult();
    }

    public function findPublished(?\App\Infrastructure\Entity\Tag $tag = null, ?string $search = null): array
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

        if ($search) {
            $qb->andWhere('p.title LIKE :search OR p.content LIKE :search OR p.subTitle LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        return $qb->getQuery()->getResult();
    }
}
