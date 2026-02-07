### Gestion des Objets avec les Factories

Pour assurer une cohérence dans la création des objets (DTO, Entités complexes, etc.) et faciliter la maintenance ainsi que les tests, nous utilisons le pattern **Factory**.

#### 1. Principes Généraux
- Chaque DTO ou objet complexe doit avoir une Factory associée.
- La Factory centralise la logique d'instanciation et d'initialisation.
- Elle permet de définir des valeurs par défaut et de simplifier la création d'objets valides pour les tests.

#### 2. Emplacement des fichiers
Les factories doivent être placées dans le namespace correspondant à la couche de l'objet qu'elles créent :
- Pour les DTO : `src/Application/Factory/`
- Pour les Entités (si nécessaire) : `src/Domain/Factory/`

#### 3. Structure d'une Factory (Exemple avec PostDTO)
Le fichier doit utiliser des méthodes statiques pour la création simple et des méthodes de convenance pour les cas d'usage fréquents.

```php
<?php
declare(strict_types=1);

namespace App\Application\Factory;

use App\Application\DTO\PostDTO;

class PostDTOFactory
{
    /**
     * Méthode de création standard
     */
    public static function create(string $title = '', string $content = ''): PostDTO
    {
        $dto = new PostDTO();
        $dto->setTitle($title);
        $dto->setContent($content);
        
        return $dto;
    }

    /**
     * Méthode pour générer un objet de test/exemple
     */
    public static function createSample(): PostDTO
    {
        return self::create(
            'Titre d\'exemple générique',
            'Contenu généré automatiquement par la factory pour les tests ou le développement.'
        );
    }
}
```

#### 4. Utilisation
Dans les contrôleurs, services ou tests, privilégiez toujours l'appel à la Factory plutôt que l'utilisation directe du mot-clé `new`.

```php
// Recommandé
$postDTO = PostDTOFactory::create($data['title'], $data['content']);

// À éviter
$postDTO = new PostDTO();
$postDTO->setTitle($data['title']);
$postDTO->setContent($data['content']);
```

#### 5. Avantages
- **Maintenance** : Si la structure du DTO change, seule la Factory doit être mise à jour.
- **Tests** : Utilisation de `createSample()` pour obtenir instantanément un objet valide.
- **Lisibilité** : Le code client est plus concis et focalisé sur son intention.
