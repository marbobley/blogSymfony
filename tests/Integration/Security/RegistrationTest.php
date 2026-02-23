<?php

declare(strict_types=1);

namespace App\Tests\Integration\Security;

use App\Infrastructure\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationTest extends WebTestCase
{
    public function testUserCanRegisterSuccessfully(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $entityManager = $container->get(EntityManagerInterface::class);

        // Supprimer l'utilisateur s'il existe déjà pour que le test soit reproductible
        $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => 'test-register@example.com']);
        if ($existingUser) {
            $entityManager->remove($existingUser);
            $entityManager->flush();
        }

        $crawler = $client->request('GET', '/register');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('S\'inscrire')->form([
            'registration[email]' => 'test-register@example.com',
            'registration[password][first]' => 'Password123!',
            'registration[password][second]' => 'Password123!',
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('/login');
        $client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', 'Votre compte a été créé avec succès');

        /** @var User|null $user */
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => 'test-register@example.com']);
        $this->assertNotNull($user);
        $this->assertSame('test-register@example.com', $user->getEmail());
    }

    public function testRegistrationValidationFailsWithDifferentPasswords(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('S\'inscrire')->form([
            'registration[email]' => 'invalid-pw@example.com',
            'registration[password][first]' => 'Password123!',
            'registration[password][second]' => 'Different123!',
        ]);

        $crawler = $client->submit($form);

        $this->assertResponseStatusCodeSame(200); // Validation error usually returns 200 if not configured for 422
        $this->assertSelectorTextContains('body', 'Les mots de passe doivent être identiques');
    }

    public function testRegistrationValidationFailsWithInvalidEmail(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('S\'inscrire')->form([
            'registration[email]' => 'invalid-email',
            'registration[password][first]' => 'Password123!',
            'registration[password][second]' => 'Password123!',
        ]);

        $crawler = $client->submit($form);

        // L'entité User lève une exception si l'email est invalide lors de sa création dans le formulaire.
        // On s'attend donc à une erreur 500 dans cet environnement de test si le validateur n'intercepte pas tout avant.
        $this->assertResponseStatusCodeSame(500);
    }
}
