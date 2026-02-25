<?php

declare(strict_types=1);

namespace App\Tests\Integration\UseCase\Like;

use App\Domain\UseCaseInterface\Like\TogglePostLikeInterface;
use App\Infrastructure\Entity\Post;
use App\Infrastructure\Entity\PostLike;
use App\Infrastructure\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TogglePostLikeIntegrationTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private TogglePostLikeInterface $togglePostLike;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->togglePostLike = $container->get(TogglePostLikeInterface::class);

        $this->cleanup();
    }

    private function cleanup(): void
    {
        $this->entityManager->createQuery('DELETE FROM ' . PostLike::class)->execute();
        $this->entityManager->createQuery('DELETE FROM ' . Post::class)->execute();
        $this->entityManager->createQuery('DELETE FROM ' . User::class)->execute();
    }

    public function testToggleLike(): void
    {
        // 1. Setup User and Post
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setPassword('password');
        $this->entityManager->persist($user);

        $post = new Post('Test Post', 'Content');
        $this->entityManager->persist($post);

        $this->entityManager->flush();

        $postId = $post->getId();
        $userId = $user->getId();

        // 2. Execute Toggle (Add)
        $this->togglePostLike->execute($postId, $userId);
        $this->entityManager->clear();

        // Check if like exists
        $like = $this->entityManager->getRepository(PostLike::class)->findOneBy([
            'post' => $postId,
            'user' => $userId
        ]);
        $this->assertNotNull($like);

        // 3. Execute Toggle (Remove)
        $this->togglePostLike->execute($postId, $userId);
        $this->entityManager->clear();

        // Check if like is removed
        $like = $this->entityManager->getRepository(PostLike::class)->findOneBy([
            'post' => $postId,
            'user' => $userId
        ]);
        $this->assertNull($like);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }
}
