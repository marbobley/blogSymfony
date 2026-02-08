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

## 3. Autorisations (Voters)

Pour la gestion fine des droits (ex: "Seul l'auteur peut modifier son post"), utilisez les **Voters** de Symfony dans `src/Infrastructure/Security/Voter`.

*   Ne mettez pas de logique de permission complexe dans les contrôleurs.
*   Utilisez l'attribut `#[IsGranted()]` ou `$this->denyAccessUnlessGranted()`.

---

## 4. Sécurité et Types Stricts

*   Toutes les classes de sécurité doivent inclure `declare(strict_types=1);`.
*   PHPStan doit être capable de valider le type de l'utilisateur retourné par `$this->getUser()`. Utilisez des annotations PHPDoc si nécessaire pour aider l'analyse statique.

---

## 5. Checklist d'Implémentation

- [ ] Le mot de passe est haché avant d'entrer dans le Domaine (via un service d'infrastructure).
- [ ] La CI passe au vert (PHPUnit & PHPStan niveau 8).
