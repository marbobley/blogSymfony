<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Infrastructure\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;

class RedditShareIntegrationTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;

    public function testRedditShareButtonIsDisplayedOnPostPage(): void
    {
        $client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);

        // Nettoyage de la base de données
        $this->entityManager->createQuery('DELETE FROM App\Infrastructure\Entity\Post')->execute();

        // 1. Arrange : Créer un article en base de données
        $title = 'Article de test pour le partage Reddit';
        $slug = 'article-de-test-partage-reddit';
        $post = new Post($title, 'Contenu de l\'article de test pour vérifier le bouton Reddit.');
        $post->setSubTitle('Sous-titre de test');
        $post->setSlug($slug);
        $post->setPublished(true);

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        // 2. Act : Accéder à la page de l'article
        $crawler = $client->request('GET', '/post/' . $slug);

        // 3. Assert
        $this->assertResponseIsSuccessful();

        // Le lien Reddit doit être présent
        $this->assertSelectorExists('a[title="Partager sur Reddit"]');

        // Vérifier que l'URL de partage contient bien l'URL de l'article et le titre
        $redditLink = $crawler->filter('a[title="Partager sur Reddit"]')->attr('href');
        $this->assertNotNull($redditLink);
        $this->assertStringContainsString('https://reddit.com/submit', (string) $redditLink);

        $currentUrl = $client->getRequest()->getUri();
        $this->assertStringContainsString('url=' . urlencode($currentUrl), (string) $redditLink);
        $this->assertStringContainsString('title=' . urlencode($title), (string) $redditLink);
    }

    protected function tearDown(): void
    {
        if (isset($this->entityManager)) {
            $this->entityManager->close();
        }
        parent::tearDown();
    }
}
