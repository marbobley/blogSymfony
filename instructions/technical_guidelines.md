# Principes Techniques du Projet

Ce document regroupe les standards de développement et les choix d'architecture pour le projet de blog.

## 1. PHP Strict Types et Qualité du Code

L'utilisation du mode strict est obligatoire dans tout le projet pour garantir la robustesse du typage.

*   **Strict Types** : Chaque fichier PHP doit commencer par `declare(strict_types=1);` immédiatement après la balise d'ouverture `<?php`.
*   **Analyse Statique** : PHPStan est utilisé au niveau 8 pour garantir la qualité du code. Toute erreur signalée par `vendor/bin/phpstan` doit être corrigée avant validation. Voir `instructions/phpstan_guidelines.md` pour plus de détails.
*   **Tests Unitaires** : La couverture par des tests unitaires (PHPUnit) est obligatoire pour toute nouvelle fonctionnalité, en particulier pour les Use Cases et les modèles de Domaine. Tout test ajouté doit être "au vert".
*   **Intégration Continue (CI)** : Un workflow GitHub Action (`.github/workflows/ci.yml`) est en place. Il exécute automatiquement PHPStan et PHPUnit à chaque push. Aucun code ne doit être fusionné si la CI est "rouge".
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
*   **Model** : Objets métier avec le suffixe `Model` (ex: `PostModel`). Ils contiennent les contraintes de validation de base.
*   **UseCaseInterface** : Définition des contrats pour les cas d'utilisation (ex: `CreatePostInterface`).
*   **UseCase** : Implémentations concrètes des cas d'utilisation (ex: `CreatePost`). Ils orchestrent la logique métier.
*   **Provider** : *Interfaces* de stockage et de récupération de données (ex: `PostProviderInterface`). Elles remplacent les interfaces de repository traditionnelles pour abstraire davantage la source de données.
*   **Exception** : Exceptions métier spécifiques.

#### B. Infrastructure (`src/Infrastructure`)
Les détails techniques et les implémentations.
*   **Entity** : Entités Doctrine (mappage DB). Elles sont distinctes des modèles du domaine.
*   **Persistence / Provider** : Implémentations concrètes des providers (ex: `DoctrinePostProvider`). Ils gèrent la conversion entre Modèles de Domaine et Entités Doctrine.
*   **Service** : Services d'infrastructure transverses (ex: `PostTagSynchronizer`).
*   **Controller** : Contrôleurs Symfony (Input HTTP).
*   **Form** : Formulaires Symfony.
*   **Twig / Components** : Composants Twig (`templates/components/`) pour modulariser le frontend.
*   **Migrations** : Fichiers de migration Doctrine.

---

## 4. Stratégie de Validation Multicouche

Pour garantir l'intégrité des données et prévenir les erreurs fatales (ex: SQL `1406 Data too long`), une validation à trois niveaux est obligatoire :

1.  **Niveau Infrastructure (Formulaires Symfony)** :
    *   Utilisez systématiquement les formulaires Symfony (`AbstractType`) pour traiter les entrées utilisateur.
    *   Appliquez des contraintes de validation (`Constraints\Length`, `NotBlank`, etc.) directement sur les champs du formulaire ou sur le DTO associé.
    *   Cela permet de fournir un feedback utilisateur immédiat et propre avant que les données n'atteignent le cœur de l'application.

2.  **Niveau Domaine (Modèle)** :
    *   Les entités du domaine doivent être les garants finaux de leur propre validité.
    *   Ajoutez des "garde-fous" (assertions ou exceptions) dans le constructeur ou les méthodes métier pour empêcher la création d'états invalides.
    *   Exemple : `if (mb_strlen($title) > 255) throw new \InvalidArgumentException(...)`.

3.  **Niveau Test (Vérification)** :
    *   Écrivez des tests unitaires pour le Domaine qui vérifient que les contraintes sont respectées et que les exceptions attendues sont bien levées en cas de données invalides.

---

## 5. Schéma du flux : Création de donnée

Voici comment circule l'information et où intervient la validation :

1.  **Utilisateur** -> Soumet des données via une requête HTTP.
2.  **Controller (Infrastructure)** :
    *   Initialise le **FormType** (Validation Niveau 1).
    *   Si le formulaire est valide, il remplit le **Modèle** (ex: `PostModel`).
    *   Appelle le **UseCaseInterface**.
3.  **UseCase (Domaine)** :
    *   Reçoit le Modèle.
    *   Appelle le **ProviderInterface** pour persister.
4.  **Persistence / Provider (Infrastructure)** :
    *   Convertit le Modèle en **Entité** Doctrine (Validation Niveau 2 - Garde-fou lors de la création de l'entité).
    *   Enregistre en base de données.
