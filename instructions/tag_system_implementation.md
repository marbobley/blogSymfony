# Instructions pour la mise en place du système de tags

Ce document détaille la proposition retenue pour l'implémentation future d'un système de tags pour les articles du blog.

## 1. Modèle de Données (Domain Layer)
- **Nouvelle Entité `Tag`** : Création de `App\Domain\Model\Tag`
    - `id` : Identifiant unique.
    - `name` : Nom du tag (ex: "PHP", "Symfony").
    - `slug` : Pour des URLs propres (via Gedmo Slugger).
- **Relation Many-to-Many** : 
    - Un `Post` peut posséder plusieurs `Tag`.
    - Un `Tag` peut être lié à plusieurs `Post`.

## 2. Évolution du Modèle `Post`
- Ajout d'une collection de tags (`ArrayCollection`).
- Méthodes `addTag(Tag $tag)` et `removeTag(Tag $tag)`.
- Mise à jour du constructeur ou de la méthode `update()` pour gérer les tags.

## 3. Persistance (Infrastructure Layer)
- Création de `TagRepositoryInterface` dans le domaine.
- Implémentation `DoctrineTagRepository` dans l'infrastructure.
- Génération d'une migration Doctrine pour les tables `tag` et `post_tag`.

## 4. Interface Utilisateur (Templates & Formulaires)
- **Formulaire** : Utilisation d'un `EntityType` pour la sélection des tags dans le formulaire de création/édition d'article.
- **Affichage** :
    - Ajout de badges pour les tags sur la page d'accueil et la liste des articles.
    - Création d'une page de filtrage par tag : `/blog/tag/{slug}`.

## 5. Étapes d'implémentation prévues
1. Création de l'entité `Tag` et de la relation avec `Post`.
2. Mise à jour des DTOs (`PostDTO`, `PostResponseDTO`) et des Factories.
3. Adaptation des formulaires Symfony.
4. Mise à jour des vues Twig.
