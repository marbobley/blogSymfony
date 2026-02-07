<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Model\Post;
use App\Domain\Repository\PostRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class DoctrinePostRepository implements PostRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function save(Post $post): void
    {
        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(Post::class)->findAll();
    }

    public function findById(int $id): ?Post
    {
        return $this->entityManager->getRepository(Post::class)->find($id);
    }

    public function findBySlug(string $slug): ?Post
    {
        return $this->entityManager->getRepository(Post::class)->findOneBy(['slug' => $slug]);
    }

    public function delete(Post $post): void
    {
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }
}
