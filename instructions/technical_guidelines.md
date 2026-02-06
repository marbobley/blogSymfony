# Principes Techniques du Projet

Ce document regroupe les standards de développement et les choix d'architecture pour le projet de blog.

## 1. PHP Strict Types et Qualité du Code

L'utilisation du mode strict est obligatoire dans tout le projet pour garantir la robustesse du typage.

*   **Strict Types** : Chaque fichier PHP doit commencer par `declare(strict_types=1);` immédiatement après la balise d'ouverture `<?php`.
*   **Typage Explicite** : Utilisez le typage fort pour les paramètres de fonction, les types de retour et les propriétés de classe.
*   **Conversion de Type** : Lors de la récupération de données depuis des sources non typées (comme `Request` de Symfony), effectuez une conversion explicite (casting) avant de les passer à des objets typés (ex: `(string) $request->request->get('title')`).

## 2. Clean Code

Le **Clean Code** consiste à écrire du code facile à lire, à comprendre et à maintenir.

### Principes clés :
*   **Naming (Nommage)** : Utilisez des noms explicites. *Exemple :* `fetchPostById()` au lieu de `get_p(id)`.
*   **Responsabilité Unique (SRP)** : Une fonction ou une classe ne doit faire qu'une seule chose.
*   **Code Auto-explicatif** : Le code doit être lisible sans commentaires superflus. Expliquez le "Pourquoi" (intention) plutôt que le "Quoi".
*   **DRY (Don't Repeat Yourself)** : Évitez la duplication.
*   **SOLID** :
    *   **S**ingle Responsibility.
    *   **O**pen/Closed (ouvert à l'extension, fermé à la modification).
    *   **L**iskov Substitution.
    *   **I**nterface Segregation.
    *   **D**ependency Inversion : Dépendre des abstractions (interfaces), pas des implémentations.

---

## 2. Architecture Hexagonale (Ports & Adapteurs)

L'objectif est d'isoler la logique métier des détails techniques (Framework, Base de données).

### Structure du code (`src/`) :

#### A. Domaine (`src/Domain`)
Le cœur du métier. Ne dépend d'aucune bibliothèque externe (ni Symfony, ni Doctrine).
*   **Model** : Objets métier (ex: `Post`).
*   **Repository** : *Interfaces* de stockage (ex: `PostRepositoryInterface`).
*   **Exception** : Exceptions métier spécifiques.

#### B. Application (`src/Application`)
Orchestre les cas d'utilisation (Use Cases).
*   **UseCaseInterface** : Définition des contrats pour les cas d'utilisation (ex: `CreatePostInterface`).
*   **UseCase** : Implémentations concrètes qui réalisent une action précise (ex: `CreatePost`).
*   **DTO** : Objets de transfert de données immuables (`readonly`) pour l'entrée/sortie du Use Case.

#### C. Infrastructure (`src/Infrastructure`)
Les détails techniques et les implémentations.
*   **Persistence** : Implémentations concrètes des repositories (ex: `DoctrinePostRepository`).
*   **Controller** : Contrôleurs Symfony (Input HTTP).
*   **Form** : Formulaires Symfony.
*   **Migrations** : Fichiers de migration Doctrine.

---

## 3. Schéma du flux : Créer un Post

Voici comment circule l'information pour le premier Use Case :

1.  **Utilisateur** -> Soumet un formulaire sur `/post/new`.
2.  **Controller (Infrastructure)** :
    *   Reçoit la requête.
    *   Valide les données via un formulaire.
    *   Transforme les données en un **DTO**.
    *   Appelle le **UseCaseInterface** (contrat injecté).
3.  **UseCase (Application)** :
    *   Reçoit le DTO.
    *   Instancie l'objet métier **Post (Domaine)**.
    *   Appelle le **RepositoryInterface (Domaine)** pour sauvegarder.
4.  **Persistence (Infrastructure)** :
    *   L'implémentation concrète (Doctrine) transforme l'objet métier en entité de base de données et persiste.
5.  **Controller** -> Redirige ou affiche un succès.
