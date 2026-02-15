# Directives de Sécurité - Blog Symfony

Ce document définit l'implémentation de la sécurité (authentification et autorisation) en respectant l'Architecture Hexagonale et les standards de qualité du projet.

## 1. Principes de Conception

La sécurité est traitée comme un détail d'infrastructure. Le cœur de l'application (Domaine) ne doit pas dépendre du composant Security de Symfony.

- **Domaine** : Définit l'entité `User`, les rôles et l'interface du repository.
- **Application** : Orchestre l'inscription et la gestion du profil via des Use Cases.
- **Infrastructure** : Implémente les interfaces de sécurité de Symfony (`UserInterface`, `UserProvider`, `Authenticator`).

---

## 2. Structure des Composants

### A. Domaine (`src/Domain`)
*   **Model/UserRegistrationModel.php** : Modèle pour les données d'inscription.
*   **UseCase/RegisterUser.php** : Gère la logique d'inscription.

### B. Infrastructure (`src/Infrastructure`)
*   **Entity/User.php** : Entité Doctrine. Elle implémente les règles de validation de base (email, etc.).
*   **Persistence/UserRepositoryInterface.php** : Interface de repository.
*   **Persistence/DoctrineUserRepository.php** : Implémentation Doctrine du repository.
*   **Controller/SecurityController.php** : Gère les routes de login/logout.

---

## 3. Autorisations et Accès

### A. Protection des Routes d'Administration
Toutes les routes d'administration (commençant par `/admin/` ou permettant la modification de données) doivent être protégées par le rôle `ROLE_ADMIN`.

*   Utilisez l'attribut `#[IsGranted('ROLE_ADMIN')]` au niveau du contrôleur ou de la méthode.
*   Exemple : `adminIndex`, `create`, `edit`, `delete` dans `PostController`.

### B. Protection des Contenus Sensibles (Brouillons)
L'accès aux ressources non publiées (ex: brouillons d'articles) doit être restreint aux utilisateurs autorisés.

*   Dans l'action `show`, vérifiez le statut de publication de la ressource.
*   Si la ressource n'est pas publiée, utilisez `$this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Message explicatif')`.

### C. Utilisation des Voters
Pour la gestion fine des droits (ex: "Seul l'auteur peut modifier son post"), utilisez les **Voters** de Symfony dans `src/Infrastructure/Security/Voter`.

*   Ne mettez pas de logique de permission complexe directement dans les contrôleurs.

### D. Hachage des Mots de Passe
Le hachage des mots de passe ne doit pas être effectué directement dans le contrôleur. 
*   **Approche recommandée** : Injecter une interface de hachage (définie dans le domaine ou une abstraction d'infrastructure) dans le Use Case d'inscription.
*   **Actuellement** : Le hachage est réalisé dans `SecurityController` avant l'appel au Use Case, ce qui est une solution temporaire à améliorer.

---

## 4. Tests de Sécurité

Chaque règle d'accès critique doit être couverte par un test d'intégration (voir `tests/Integration/Security/DraftAccessTest.php`).

Les tests doivent valider :
1.  L'accès public aux ressources publiées.
2.  La redirection vers le login (ou erreur 403) pour les utilisateurs non authentifiés tentant d'accéder à un brouillon ou une route d'admin.
3.  L'accès autorisé pour les utilisateurs possédant le rôle `ROLE_ADMIN`.

---

## 5. Sécurité et Types Stricts

*   Toutes les classes de sécurité doivent inclure `declare(strict_types=1);`.
*   PHPStan doit être capable de valider le type de l'utilisateur retourné par `$this->getUser()`. Utilisez des annotations PHPDoc si nécessaire pour aider l'analyse statique.

---

## 5. Checklist d'Implémentation

- [ ] Le mot de passe est haché avant d'entrer dans le Domaine (via un service d'infrastructure).
- [ ] La CI passe au vert (PHPUnit & PHPStan niveau 8).
