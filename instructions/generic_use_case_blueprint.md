# Blueprint de Cas d'Utilisation (Générique)

Ce document sert de guide de référence pour implémenter n'importe quel nouveau cas d'utilisation (Use Case) dans ce projet en respectant l'Architecture Hexagonale et le Clean Code.

**Note importante :** Avant toute implémentation, consultez les [Bonnes Pratiques Clean Code](clean_code_best_practices.md) pour éviter les erreurs de conception communes (Flag arguments, violation du SRP, etc.).

## 🛠 Cycle de Développement (Inside-Out)

**Règle d'or :** Chaque nouveau fichier PHP créé doit impérativement commencer par `declare(strict_types=1);`.

On commence toujours par le cœur (Domaine) pour finir par les détails techniques (Infrastructure).

---

### 🟢 Phase 1 : Domaine (Cœur Métier)
*Lieu : `src/Domain`*

1.  **Modèle (`Model/`)** : Utiliser ou créer un modèle avec le suffixe `Model` (ex: `PostModel`).
2.  **Contrat de Provider (`Provider/`)** : Définir les méthodes nécessaires dans l'interface (ex: `PostProviderInterface`).
    *   *Règle :* On définit ce dont on a besoin fonctionnellement (save, find, delete).
3.  **Interface de Use Case (`UseCaseInterface/`)** : Définir le contrat du service (ex: `ListPostsInterface`).
4.  **Implémentation du Use Case (`UseCase/`)** :
    *   Injecter les interfaces de provider nécessaires en `readonly`.
    *   Implémenter la logique d'orchestration.

### 🔴 Phase 2 : Infrastructure (Implémentation Technique)
*Lieu : `src/Infrastructure` & `templates/`*

1.  **Entité (`Entity/`)** : Créer ou mettre à jour l'entité Doctrine.
2.  **Persistance / Repository (`Persistence/` ou `Repository/`)** : Implémenter le provider concret (ex: `DoctrinePostProvider`). Il gère la conversion Modèle <-> Entité.
3.  **Contrôleur (`Controller/`)** :
    *   Injecter l'interface du Use Case.
    *   Appeler le Use Case avec le Modèle de domaine.
    *   Retourner une réponse.
4.  **Vues (`templates/`)** : Créer ou mettre à jour les fichiers Twig.

---

### ⚙️ Phase 3 : Branchements (Configuration)
*Lieu : `config/`*

1.  **Services (`services.yaml`)** : Déclarer l'alias entre l'interface du Use Case et son implémentation, ainsi que pour les Providers.
2.  **Routes (`routes.yaml` ou attributs)** : Vérifier que la route est bien définie.

---

## 📖 Exemple : Use Case "Afficher les posts"

### 1. Domaine
- `PostCriteria` : Objet encapsulant les filtres (search, tag, etc.).
- `PostProviderInterface` : Ajouter `public function findByCriteria(PostCriteria $criteria): array;`.
- `ListPublishedPostsInterface` : `public function execute(?PostCriteria $criteria = null): array;`
- `ListPublishedPosts` : Implémentation qui instancie un `PostCriteria` (si nul) forçant `onlyPublished: true` et appelle le provider.

### 2. Infrastructure
- `DoctrinePostRepository` (via `PostAdapter`) : Implémenter `findByCriteria()` en utilisant le `QueryBuilder` de Doctrine.
- `PostController` : Méthode `index()` qui appelle `ListPublishedPostsInterface` avec un objet `PostCriteria`.
- `templates/post/index.html.twig` : Boucle `for post in posts`.

### 3. Configuration
- `services.yaml` :
  ```yaml
  App\Domain\UseCaseInterface\ListPublishedPostsInterface:
      alias: App\Domain\UseCase\ListPublishedPosts
  ```
