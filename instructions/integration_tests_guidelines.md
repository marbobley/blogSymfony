# Guide des Tests d'Intégration

Ce document explique comment mettre en place et rédiger des tests d'intégration dans ce projet.

## 1. Objectifs

Les tests d'intégration vérifient que les différents composants de l'application (Domaine, Infrastructure, Base de données, Framework) fonctionnent correctement ensemble. Contrairement aux tests unitaires, ils utilisent le noyau Symfony complet et une véritable base de données.

## 2. Configuration de l'environnement

### Base de données de test
Pour les tests d'intégration, nous utilisons une base de données séparée. Elle est configurée via le fichier `.env.test` ou des variables d'environnement.

Assurez-vous que la base de données de test est créée et à jour :
```bash
symfony --env=test doctrine:database:create
symfony --env=test doctrine:migrations:migrate
```

### Outils
- **PHPUnit** : Le moteur de test.
- **DAMA/DoctrineTestBundle** (Recommandé) : Pour isoler chaque test dans une transaction et éviter de polluer la base de données. *Note : Vérifier s'il est installé via `composer.json`.*

## 3. Classes de base

### KernelTestCase
Utilisé pour tester des services qui dépendent du conteneur de services Symfony (ex: Repositories, Use Cases avec leurs dépendances réelles).

```php
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MyServiceTest extends KernelTestCase
{
    public function testSomething(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $service = $container->get(MyServiceInterface::class);
        // ...
    }
}
```

### WebTestCase
Utilisé pour tester les contrôleurs et le rendu des pages. Il permet de simuler un navigateur.

```php
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    public function testIndexPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Bienvenue sur le blog');
    }
}
```

## 4. Gestion des données (Fixtures)

Pour les tests d'intégration, nous utilisons `doctrine/doctrine-fixtures-bundle` pour peupler la base de données avant les tests.

Exemple d'utilisation dans un test :
```php
protected function setUp(): void
{
    self::bootKernel();
    // Logique pour charger les fixtures si nécessaire
}
```

## 5. Structure des fichiers

Les tests d'intégration doivent être placés dans le répertoire `tests/Integration`.
- `tests/Integration/Persistence` : Tests pour les repositories.
- `tests/Integration/Controller` : Tests pour les contrôleurs.

## 6. Bonnes pratiques

1.  **Isolation** : Chaque test doit être indépendant. Utilisez des transactions ou réinitialisez la base de données entre chaque test.
2.  **Rapidité** : Ne testez que ce qui nécessite une intégration réelle. Privilégiez les tests unitaires pour la logique métier pure.
3.  **Fixtures ciblées** : Ne chargez que les données nécessaires au test en cours.
4.  **Assertions spécifiques** : Utilisez les assertions Symfony (`assertResponseIsSuccessful`, `assertSelectorExists`, etc.).
5.  **Initialisation des Modèles** : Pour éviter les "Notices" PHPUnit (notamment sur les propriétés typées non initialisées), assurez-vous que vos modèles de Domaine (`PostModel`, `TagModel`, etc.) initialisent toutes leurs propriétés typées avec des valeurs par défaut ou via le constructeur. Les propriétés `id` doivent être nullables (`?int`) et initialisées à `null`.
