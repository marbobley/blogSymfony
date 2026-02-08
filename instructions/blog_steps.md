# Guide de développement du Blog (Approche Hexagonale)

Ce document décrit les étapes fonctionnelles pour construire le blog, adaptées à une architecture propre.

## Étape 1 : Modélisation du Domaine (Le cœur métier)
Contrairement à l'approche standard, on ne commence pas par la base de données ou le `MakerBundle`.

1.  **Identifier l'objet métier** : Créer la classe `PostModel` dans `src/Domain/Model`. Elle contient les propriétés et les contraintes de validation (`#[Assert]`).
2.  **Définir l'interface de stockage** : Créer `PostProviderInterface` dans `src/Domain/Provider`. On définit *ce dont on a besoin* (ex: `save(PostModel $post)`, `findBySlug(string $slug)`).

## Étape 2 : Création du Cas d'Utilisation (Domaine)
1.  **Définir l'interface du Use Case** : Créer les interfaces (ex: `CreatePostInterface`) dans `src/Domain/UseCaseInterface`.
2.  **Créer le Use Case** : La classe implémente l'interface dans `src/Domain/UseCase`. Elle orchestre l'action et utilise le provider.
3.  **Tester la logique (Obligatoire)** : Créer des tests unitaires dans `tests/Unit/Domain/UseCase`. Tester les cas nominaux et les cas d'erreur.

## Étape 3 : Implémentation technique (Infrastructure)
C'est seulement ici que l'on connecte les outils (Symfony & Doctrine).

1.  **Entité Doctrine** : Créer `Post` dans `src/Infrastructure/Entity`. C'est ici qu'on utilise les annotations `#[ORM]` et `#[Gedmo\Slug]`.
2.  **Persistence / Provider** : Créer `DoctrinePostProvider` dans `src/Infrastructure/Persistence` (ou `src/Infrastructure/Provider`). Il implémente `PostProviderInterface` et fait le pont entre `PostModel` et l'entité `Post`.
3.  **Formulaire & Validation** : Créer un `PostType` dans `src/Infrastructure/Form`.
4.  **Contrôleur Symfony** : Créer un contrôleur dans `src/Infrastructure/Controller` qui :
    *   Traite le formulaire avec le `PostModel`.
    *   Appelle l'interface du Use Case.
5.  **Vues Twig** : Créer les templates dans `templates/`.

## Étape 4 : Configuration & DB
1.  **Fichier .env** : Configurer la `DATABASE_URL`.
2.  **Migrations** : Générer les tables à partir des entités (qui peuvent être mappées directement depuis le Domaine ou via des entités d'infrastructure).

---

## Challenge des étapes (Hexa vs Standard)

| Étape Standard | Challange Hexagonal | Pourquoi ? |
| :--- | :--- | :--- |
| `make:entity` | **Modélisation manuelle** | L'entité ne doit pas dépendre de Doctrine au début. |
| `make:crud` | **Découpage manuel** | Le CRUD généré mélange contrôleur et logique Doctrine. En Hexa, on sépare le contrôleur du Use Case (via une Interface). |
| DB d'abord | **Domaine d'abord** | La base de données n'est qu'un détail d'implémentation. Le métier est prioritaire. |
