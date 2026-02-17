<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Infrastructure\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;

class LinkedInShareIntegrationTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;

    public function testLinkedInShareButtonIsDisplayedOnPostPage(): void
    {
        $client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);

        // Nettoyage de la base de données
        $this->entityManager->createQuery('DELETE FROM App\Infrastructure\Entity\Post')->execute();

        // 1. Arrange : Créer un article en base de données
        $title = 'Article de test pour le partage LinkedIn';
        $slug = 'article-de-test-partage-linkedin';
        $post = new Post($title, 'Contenu de l\'article de test pour vérifier le bouton LinkedIn.');
        $post->setSubTitle('Sous-titre de test');
        $post->setSlug($slug);
        $post->publish();

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        // 2. Act : Accéder à la page de l'article
        $crawler = $client->request('GET', '/post/' . $slug);

        // 3. Assert
        $this->assertResponseIsSuccessful();

        // Le lien LinkedIn doit être présent
        $this->assertSelectorExists('a[title="Partager sur LinkedIn"]');

        // Vérifier que l'URL de partage contient bien l'URL de l'article
        $linkedInLink = $crawler->filter('a[title="Partager sur LinkedIn"]')->attr('href');
        $this->assertNotNull($linkedInLink);
        $this->assertStringContainsString('https://www.linkedin.com/sharing/share-offsite/', (string) $linkedInLink);

        // L'URL de l'article devrait être encodée dans le lien
        $currentUrl = $client->getRequest()->getUri();
        $this->assertStringContainsString(urlencode($currentUrl), (string) $linkedInLink);
    }

    protected function tearDown(): void
    {
        if (isset($this->entityManager)) {
            $this->entityManager->close();
        }
        parent::tearDown();
    }
}
