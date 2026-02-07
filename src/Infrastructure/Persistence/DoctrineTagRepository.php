<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Model\Tag;
use App\Domain\Repository\TagRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<Tag>
 */
class DoctrineTagRepository implements TagRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function save(Tag $tag): void
    {
        $this->entityManager->persist($tag);
        $this->entityManager->flush();
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(Tag::class)->findAll();
    }

    public function findById(int $id): ?Tag
    {
        return $this->entityManager->getRepository(Tag::class)->find($id);
    }

    public function findBySlug(string $slug): ?Tag
    {
        return $this->entityManager->getRepository(Tag::class)->findOneBy(['slug' => $slug]);
    }

    public function delete(Tag $tag): void
    {
        $this->entityManager->remove($tag);
        $this->entityManager->flush();
    }
}
