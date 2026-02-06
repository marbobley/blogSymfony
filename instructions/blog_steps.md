# Guide de développement du Blog (Approche Hexagonale)

Ce document décrit les étapes fonctionnelles pour construire le blog, adaptées à une architecture propre.

## Étape 1 : Modélisation du Domaine (Le cœur métier)
Contrairement à l'approche standard, on ne commence pas par la base de données ou le `MakerBundle`.

1.  **Identifier l'objet métier** : Créer la classe `Post` dans `src/Domain/Model`. Elle doit contenir la logique métier (ex: validation interne, gestion des états).
2.  **Définir l'interface de stockage** : Créer `PostRepositoryInterface` dans `src/Domain/Repository`. On définit *ce dont on a besoin* (ex: `save(Post $post)`), pas comment c'est fait.

## Étape 2 : Création du Cas d'Utilisation (Application)
1.  **Créer le Use Case `CreatePost`** : Cette classe va orchestrer la création d'un article. Elle reçoit des données simples (DTO), crée l'objet `Post` et utilise le repository.
2.  **Tester la logique** : C'est ici que l'on peut faire des tests unitaires très rapides sans base de données (en utilisant un repository en mémoire).

## Étape 3 : Implémentation technique (Infrastructure)
C'est seulement ici que l'on connecte les outils (Symfony & Doctrine).

1.  **Persistence Doctrine** : Créer le repository concret dans `src/Infrastructure/Persistence`. C'est lui qui implémente `PostRepositoryInterface`.
2.  **Contrôleur Symfony** : Créer un contrôleur dans `src/Infrastructure/Controller` qui reçoit les requêtes HTTP et appelle le Use Case de l'étape 2.
3.  **Vues Twig** : Créer les templates dans `templates/`.

## Étape 4 : Configuration & DB
1.  **Fichier .env** : Configurer la `DATABASE_URL`.
2.  **Migrations** : Générer les tables à partir des entités (qui peuvent être mappées directement depuis le Domaine ou via des entités d'infrastructure).

---

## Challenge des étapes (Hexa vs Standard)

| Étape Standard | Challange Hexagonal | Pourquoi ? |
| :--- | :--- | :--- |
| `make:entity` | **Modélisation manuelle** | L'entité ne doit pas dépendre de Doctrine au début. |
| `make:crud` | **Découpage manuel** | Le CRUD généré mélange contrôleur et logique Doctrine. En Hexa, on sépare le contrôleur du Use Case. |
| DB d'abord | **Domaine d'abord** | La base de données n'est qu'un détail d'implémentation. Le métier est prioritaire. |
