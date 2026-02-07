# Guide de développement du Blog (Approche Hexagonale)

Ce document décrit les étapes fonctionnelles pour construire le blog, adaptées à une architecture propre.

## Étape 1 : Modélisation du Domaine (Le cœur métier)
Contrairement à l'approche standard, on ne commence pas par la base de données ou le `MakerBundle`.

1.  **Identifier l'objet métier** : Créer la classe `Post` dans `src/Domain/Model`. Elle doit contenir la logique métier (ex: validation interne, gestion des états).
2.  **Gérer les Slugs** : Pour les URLs conviviales, utiliser l'extension `Gedmo\Slug`. Ajouter la propriété `slug` et l'annoter avec `#[Gedmo\Slug(fields: ['title'])]`. S'assurer que le getter `getSlug()` autorise un retour `null` car le slug est généré lors de la persistence.
3.  **Définir l'interface de stockage** : Créer `PostRepositoryInterface` dans `src/Domain/Repository`. On définit *ce dont on a besoin* (ex: `save(Post $post)`, `findBySlug(string $slug)`), pas comment c'est fait.

## Étape 2 : Création du Cas d'Utilisation (Application)
1.  **Définir l'interface du Use Case** : Créer les interfaces (ex: `CreatePostInterface`, `GetPostBySlugInterface`) dans `src/Application/UseCaseInterface`. Cela permet de découpler le contrôleur de l'implémentation.
2.  **Créer le Use Case** : Cette classe implémente l'interface. Elle orchestre l'action, reçoit un DTO immuable, manipule l'objet du Domaine et utilise le repository (en `readonly`).
3.  **Tester la logique (Obligatoire)** : Créer des tests unitaires dans `tests/Unit/Application/UseCase`. Tester les cas nominaux et les cas d'erreur (ex: article non trouvé).
    *   *Note sur les Slugs* : Lors des tests unitaires, le slug d'une nouvelle entité sera `null` car l'extension Doctrine n'est pas active en test unitaire pur. Le `PostResponseDTOFactory` doit gérer cela (ex: via un `?? ''`).

## Étape 3 : Implémentation technique (Infrastructure)
C'est seulement ici que l'on connecte les outils (Symfony & Doctrine).

1.  **Formulaire & Validation** : Créer un `PostType` dans `src/Infrastructure/Form`. Appliquer les contraintes de validation (`Length`, `NotBlank`) pour une validation au niveau de l'infrastructure.
2.  **Contrôleur Symfony** : Créer un contrôleur dans `src/Infrastructure/Controller` qui :
    *   Traite le formulaire.
    *   Vérifie sa validité.
    *   Appelle l'interface du Use Case en lui passant un DTO.
    *   Utilise le **slug** pour les routes d'affichage public (`/post/{slug}`) et l'**ID** pour les actions d'administration (`/post/edit/{id}`).
3.  **Persistence Doctrine** : Créer le repository concret dans `src/Infrastructure/Persistence`. C'est lui qui implémente `PostRepositoryInterface`.
4.  **Vues Twig** : Créer les templates dans `templates/`. Utiliser `post.slug` pour générer les liens vers les articles.

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
