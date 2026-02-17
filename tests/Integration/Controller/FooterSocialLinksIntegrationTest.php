<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FooterSocialLinksIntegrationTest extends WebTestCase
{
    public function testFooterSocialLinksAreDisplayedOnHomePage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        // Vérifier la présence du lien GitHub
        $this->assertSelectorExists('a[aria-label="Lien vers GitHub"]');
        $githubLink = $crawler->filter('a[aria-label="Lien vers GitHub"]')->attr('href');
        $this->assertEquals('https://github.com/marbobley', $githubLink);

        // Vérifier la présence du lien BlueSky
        $this->assertSelectorExists('a[aria-label="Lien vers BlueSky"]');
        $blueskyLink = $crawler->filter('a[aria-label="Lien vers BlueSky"]')->attr('href');
        $this->assertEquals('https://bsky.app/profile/marbobley.bsky.social', $blueskyLink);

        // Vérifier la présence du lien Site Web
        $this->assertSelectorExists('a[aria-label="Lien vers Site Web"]');
        $websiteLink = $crawler->filter('a[aria-label="Lien vers Site Web"]')->attr('href');
        $this->assertEquals('https://wfdevelopment.fr/home', $websiteLink);
    }
}
