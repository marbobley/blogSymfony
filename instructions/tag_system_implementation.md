# Système de Tags (Implémentation)

Ce document détaille l'implémentation du système de tags pour les articles du blog.

## 1. Modèle de Données (Domain Layer)
- **Modèle `TagModel`** : `App\Domain\Model\TagModel`
    - `id` : Identifiant.
    - `name` : Nom du tag.
    - `slug` : Identifiant URL.
- **Modèle `PostModel`** : Possède une collection de `TagModel`.

## 2. Infrastructure Layer
- **Entité `Tag`** : `App\Infrastructure\Entity\Tag` (Doctrine).
- **Entité `Post`** : Gère la relation Many-to-Many avec `Tag`.
- **Synchronisation** : `App\Infrastructure\Service\PostTagSynchronizer`
    - Ce service est responsable de la synchronisation des tags entre le `PostModel` et l'entité `Post` lors de la persistence.
    - Il crée les nouveaux tags s'ils n'existent pas en base de données.

## 3. Persistance
- **`TagProviderInterface`** : Définit les opérations de recherche et sauvegarde des tags.
- **`DoctrineTagProvider`** : Implémentation utilisant Doctrine.

## 4. Interface Utilisateur
- **Formulaire** : `PostType` utilise un `TextType` ou un composant personnalisé pour saisir les tags sous forme de texte, convertis ensuite en `TagModel`.
- **Affichage** : Les tags sont affichés sous forme de badges dans les listes et les pages d'articles.
