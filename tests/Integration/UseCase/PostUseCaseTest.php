<?php

declare(strict_types=1);

namespace App\Tests\Integration\UseCase;

use App\Domain\Criteria\PostCriteria;
use App\Domain\Model\PostModel;
use App\Domain\UseCaseInterface\CreatePostInterface;
use App\Domain\UseCaseInterface\DeletePostInterface;
use App\Domain\UseCaseInterface\GetPostInterface;
use App\Domain\UseCaseInterface\ListAllPostsInterface;
use App\Domain\UseCaseInterface\ListPublishedPostsInterface;
use App\Domain\UseCaseInterface\UpdatePostInterface;
use App\Infrastructure\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PostUseCaseTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private CreatePostInterface $createPost;
    private ListAllPostsInterface $listAllPosts;
    private ListPublishedPostsInterface $listPublishedPosts;
    private UpdatePostInterface $updatePost;
    private DeletePostInterface $deletePost;
    private GetPostInterface $getPost;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->createPost = $container->get(CreatePostInterface::class);
        $this->listAllPosts = $container->get(ListAllPostsInterface::class);
        $this->listPublishedPosts = $container->get(ListPublishedPostsInterface::class);
        $this->updatePost = $container->get(UpdatePostInterface::class);
        $this->deletePost = $container->get(DeletePostInterface::class);
        $this->getPost = $container->get(GetPostInterface::class);

        $this->cleanup();
    }

    private function cleanup(): void
    {
        $this->entityManager->createQuery('DELETE FROM App\Infrastructure\Entity\Post')->execute();
        $this->entityManager->createQuery('DELETE FROM App\Infrastructure\Entity\Tag')->execute();
    }

    public function testCreatePost(): void
    {
        $postModel = new PostModel();
        $postModel->setTitle('Nouveau post d\'intégration');
        $postModel->setSubTitle('Sous-titre du post');
        $postModel->setContent('Contenu détaillé du post pour le test d\'intégration.');

        $result = $this->createPost->execute($postModel);

        $this->assertNotNull($result->getId());
        $this->assertEquals('Nouveau post d\'intégration', $result->getTitle());

        $dbPost = $this->entityManager->find(Post::class, $result->getId());
        $this->assertNotNull($dbPost);
        $this->assertEquals('Nouveau post d\'intégration', $dbPost->getTitle());
    }

    public function testListPosts(): void
    {
        $post1 = new Post('Titre article 1', 'Contenu 1');
        $post1->setSubTitle('Sub 1');
        $post1->setPublished(true);
        $this->entityManager->persist($post1);

        $post2 = new Post('Titre article 2', 'Contenu 2');
        $post2->setSubTitle('Sub 2');
        $post2->setPublished(false);
        $this->entityManager->persist($post2);

        $this->entityManager->flush();

        $allPublished = $this->listPublishedPosts->execute(new PostCriteria());
        $this->assertCount(1, $allPublished);

        $all = $this->listAllPosts->execute(new PostCriteria());
        $this->assertCount(2, $all);
    }

    public function testUpdatePost(): void
    {
        $post = new Post('Ancien titre', 'Ancien contenu');
        $post->setSubTitle('Ancien sub');
        $this->entityManager->persist($post);
        $this->entityManager->flush();

        $initialUpdatedAt = $post->getUpdatedAt();

        // On attend un peu pour être sûr que le timestamp change si la résolution est à la seconde
        sleep(1);

        $updateModel = new PostModel();
        $updateModel->setTitle('Nouveau titre mis à jour');
        $updateModel->setSubTitle('Nouveau sub');
        $updateModel->setContent('Nouveau contenu');

        $result = $this->updatePost->execute($post->getId(), $updateModel);

        $this->assertEquals('Nouveau titre mis à jour', $result->getTitle());
        $this->assertNotNull($result->getUpdatedAt());

        if ($initialUpdatedAt !== null) {
            $this->assertGreaterThan($initialUpdatedAt->getTimestamp(), $result->getUpdatedAt()->getTimestamp());
        }

        $this->entityManager->clear();
        $dbPost = $this->entityManager->find(Post::class, $post->getId());
        $this->assertEquals('Nouveau titre mis à jour', $dbPost->getTitle());
        $this->assertNotNull($dbPost->getUpdatedAt());
    }

    public function testGetPost(): void
    {
        $post = new Post('Titre à récupérer', 'Contenu à récupérer');
        $post->setSubTitle('Sub');
        $this->entityManager->persist($post);
        $this->entityManager->flush();

        $result = $this->getPost->execute($post->getId());

        $this->assertEquals('Titre à récupérer', $result->getTitle());
        $this->assertEquals($post->getId(), $result->getId());
    }

    public function testDeletePost(): void
    {
        $post = new Post('Titre à supprimer', 'Contenu');
        $post->setSubTitle('Sub');
        $this->entityManager->persist($post);
        $this->entityManager->flush();

        $id = $post->getId();
        $this->deletePost->execute($id);

        $this->entityManager->clear();
        $dbPost = $this->entityManager->find(Post::class, $id);
        $this->assertNull($dbPost);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }
}
