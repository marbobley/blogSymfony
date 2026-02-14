<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Infrastructure\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;

class BlueskyShareIntegrationTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;

    public function testBlueskyShareButtonIsDisplayedOnPostPage(): void
    {
        $client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);

        // Nettoyage de la base de données
        $this->entityManager->createQuery('DELETE FROM App\Infrastructure\Entity\Post')->execute();

        // 1. Arrange : Créer un article en base de données
        $title = 'Article de test pour le partage Bluesky';
        $slug = 'article-de-test-partage-bluesky';
        $post = new Post($title, 'Contenu de l\'article de test pour vérifier le bouton Bluesky.');
        $post->setSubTitle('Sous-titre de test');
        $post->setSlug($slug);
        $post->setPublished(true);

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        // 2. Act : Accéder à la page de l'article
        $crawler = $client->request('GET', '/post/' . $slug);

        // 3. Assert
        $this->assertResponseIsSuccessful();

        // Le lien Bluesky doit être présent
        $this->assertSelectorExists('a[title="Partager sur Bluesky"]');

        // Vérifier que l'URL de partage est correcte
        $blueskyLink = $crawler->filter('a[title="Partager sur Bluesky"]')->attr('href');
        $this->assertNotNull($blueskyLink);
        $this->assertStringContainsString('https://bsky.app/intent/compose', (string) $blueskyLink);

        // L'URL de l'article et le titre devraient être encodés dans le lien
        $currentUrl = $client->getRequest()->getUri();
        $this->assertStringContainsString(urlencode($title), (string) $blueskyLink);
        $this->assertStringContainsString(urlencode($currentUrl), (string) $blueskyLink);
    }

    protected function tearDown(): void
    {
        if (isset($this->entityManager)) {
            $this->entityManager->close();
        }
        parent::tearDown();
    }
}
