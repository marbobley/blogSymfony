# Blueprint de Cas d'Utilisation (Générique)

Ce document sert de guide de référence pour implémenter n'importe quel nouveau cas d'utilisation (Use Case) dans ce projet en respectant l'Architecture Hexagonale et le Clean Code.

## 🛠 Cycle de Développement (Inside-Out)

On commence toujours par le cœur (Domaine) pour finir par les détails techniques (Infrastructure).

---

### 🟢 Phase 1 : Domaine (Cœur Métier)
*Lieu : `src/Domain`*

1.  **Modèle (`Model/`)** : Si nécessaire, ajouter des méthodes métier à l'entité (ex: `Post`). L'entité doit rester "pure".
2.  **Contrat de Repository (`Repository/`)** : Définir les méthodes nécessaires dans l'interface (ex: `findAll(): array`).
    *   *Règle :* On définit ce dont on a besoin fonctionnellement, sans penser à la base de données.

### 🔵 Phase 2 : Application (Logique Applicative)
*Lieu : `src/Application`*

1.  **DTO (`DTO/`)** : Créer des objets `readonly` pour les entrées (RequestDTO) et les sorties (ResponseDTO/ViewModel).
2.  **Interface de Use Case (`UseCaseInterface/`)** : Définir le contrat du service (ex: `ListPostsInterface`).
3.  **Implémentation du Use Case (`UseCase/`)** :
    *   Injecter les interfaces de repository nécessaires en `readonly`.
    *   Implémenter la logique d'orchestration.
    *   Transformer les objets du domaine en DTO de sortie.

### 🔴 Phase 3 : Infrastructure (Implémentation Technique)
*Lieu : `src/Infrastructure` & `templates/`*

1.  **Persistance (`Persistence/`)** : Implémenter les nouvelles méthodes dans le repository concret (ex: `DoctrinePostRepository`).
2.  **Contrôleur (`Controller/`)** :
    *   Injecter l'interface du Use Case.
    *   Récupérer les données (si besoin).
    *   Appeler le Use Case.
    *   Retourner une réponse (HTML via Twig ou JSON).
3.  **Vues (`templates/`)** : Créer ou mettre à jour les fichiers Twig.

---

### ⚙️ Phase 4 : Branchements (Configuration)
*Lieu : `config/`*

1.  **Services (`services.yaml`)** : Déclarer l'alias entre l'interface du Use Case et son implémentation.
2.  **Routes (`routes.yaml` ou attributs)** : Vérifier que la route est bien définie.

---

## 📖 Exemple : Use Case "Afficher les posts"

### 1. Domaine
- `PostRepositoryInterface` : Ajouter `public function findAll(): array;`

### 2. Application
- `PostResponseDTO` : Un DTO pour l'affichage (id, titre, résumé...).
- `ListPostsInterface` : `public function execute(): array;`
- `ListPosts` : Implémentation qui appelle `$this->repository->findAll()`.

### 3. Infrastructure
- `DoctrinePostRepository` : Implémenter `findAll()` via QueryBuilder ou `parent::findAll()`.
- `PostController` : Nouvelle méthode `index()` qui appelle `ListPostsInterface`.
- `templates/post/index.html.twig` : Boucle `for post in posts`.

### 4. Configuration
- `services.yaml` :
  ```yaml
  App\Application\UseCaseInterface\ListPostsInterface:
      alias: App\Application\UseCase\ListPosts
  ```
