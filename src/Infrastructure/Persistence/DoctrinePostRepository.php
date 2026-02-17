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

    /**
     * @return Post[]
     */
    public function findByCriteria(\App\Domain\Criteria\PostCriteria $criteria): array
    {
        $qb = $this->entityManager
            ->createQueryBuilder()
            ->select('p')
            ->from(Post::class, 'p');

        if ($criteria->isOnlyPublished()) {
            $qb->andWhere('p.published = :published')->setParameter(key: 'published', value: true);
        }

        if ($criteria->getTagId() !== null) {
            $qb->join(join: 'p.tags', alias: 't')->andWhere('t.id = :tagId')->setParameter(key: 'tagId', value: $criteria->getTagId());
        }

        $search = $criteria->getSearch();
        if ($search !== null && $search !== '') {
            $qb->andWhere('p.title LIKE :search OR p.content LIKE :search OR p.subTitle LIKE :search')->setParameter(
                key: 'search',
                value: '%' . $search . '%',
            );
        }

        /** @var Post[] $result */
        $result = $qb->getQuery()->getResult();
        return $result;
    }
}
