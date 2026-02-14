<?php

declare(strict_types=1);

namespace App\Tests\Integration\Security;

use App\Infrastructure\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;

class DraftAccessTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        // On ne boot pas le kernel ici, createClient le fera
    }

    public function testUnauthenticatedUserCannotAccessDraft(): void
    {
        $client = static::createClient();
        $this->entityManager = $client->getContainer()->get(EntityManagerInterface::class);
        $this->entityManager->createQuery('DELETE FROM App\Infrastructure\Entity\Post')->execute();

        // Création d'un post non publié (brouillon)
        $post = new Post('Draft Post Title', 'Draft Post Content');
        $post->setSubTitle('Sub title');
        $post->setSlug('draft-post');
        $post->setPublished(false);

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        // Tentative d'accès au brouillon via son slug
        $client->request('GET', '/post/draft-post');

        // On s'attend à une redirection vers la page de login (302) car l'utilisateur n'est pas connecté
        $this->assertResponseRedirects('/login');
    }

    public function testPublishedPostIsStillAccessibleByUnauthenticatedUser(): void
    {
        $client = static::createClient();
        $this->entityManager = $client->getContainer()->get(EntityManagerInterface::class);
        $this->entityManager->createQuery('DELETE FROM App\Infrastructure\Entity\Post')->execute();

        // Création d'un post publié
        $post = new Post('Published Post Title', 'Published Post Content');
        $post->setSubTitle('Sub title');
        $post->setSlug('published-post');
        $post->setPublished(true);

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        // Tentative d'accès au post publié
        $client->request('GET', '/post/published-post');

        $this->assertResponseIsSuccessful();
    }
}
