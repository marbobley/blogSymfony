<?php

declare(strict_types=1);

namespace App\Tests\Integration\UseCase;

use App\Domain\UseCaseInterface\GetPostBySlugInterface;
use App\Infrastructure\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;

class GetPostBySlugTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private GetPostBySlugInterface $useCase;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->useCase = $container->get(GetPostBySlugInterface::class);

        // Nettoyage de la base de données (si DAMA/DoctrineTestBundle n'est pas utilisé ou mal configuré)
        $this->entityManager->createQuery('DELETE FROM App\Infrastructure\Entity\Post')->execute();
    }

    public function testExecuteReturnsPostModelWhenSlugExists(): void
    {
        // 1. Arrange : Créer un post en base de données
        $title = 'Un titre d\'article suffisamment long';
        $content = 'Ceci est le contenu de l\'article qui est également assez long pour passer les validations si nécessaire.';
        $post = new Post($title, $content);
        $post->setSubTitle('Ceci est un sous-titre');
        $post->setSlug('test-post'); // On force le slug pour le test

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        // 2. Act : Appeler le Use Case
        $result = $this->useCase->execute('test-post');

        // 3. Assert : Vérifier le résultat
        $this->assertEquals($title, $result->getTitle());
        $this->assertEquals($content, $result->getContent());
        $this->assertEquals('test-post', $result->getSlug());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }
}
