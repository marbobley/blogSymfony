<?php

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
}
