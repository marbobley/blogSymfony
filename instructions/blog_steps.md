# Marche à suivre pour créer un projet de Blog Symfony

Voici les étapes recommandées pour construire votre blog avec Symfony :

### 1. Configuration de la base de données
Avant de créer quoi que ce soit, assurez-vous que votre fichier `.env` est correctement configuré avec vos accès à la base de données.
```text
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=8.0.x&charset=utf8mb4"
```
Ensuite, créez la base de données :
```bash
php bin/console doctrine:database:create
```

### 2. Création de l'entité Article (Post)
C'est le cœur du blog. Utilisez le `MakerBundle` pour générer l'entité et ses champs (titre, contenu, date de création, etc.).
```bash
php bin/console make:entity Post
```

### 3. Migration de la base de données
Une fois l'entité créée, il faut mettre à jour la structure de la base de données :
```bash
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

### 4. Génération du CRUD (Interface d'administration)
Symfony peut générer automatiquement les formulaires, les contrôleurs et les vues pour gérer les articles.
```bash
php bin/console make:crud Post
```

### 5. Personnalisation du Design
Modifiez les fichiers Twig dans `templates/` pour personnaliser l'apparence. Le fichier principal est `templates/base.html.twig`.
Utilisez `assets/` pour votre CSS et JS via AssetMapper.

### 6. Fonctionnalités avancées
*   **Sécurité** : `php bin/console make:user` et `php bin/console make:auth`.
*   **Commentaires** : Créer une entité `Comment` liée à `Post`.
*   **Catégories** : Pour organiser vos articles.
*   **Fixtures** : Pour générer des données de test (`composer require --dev orm-fixtures`).

---

## Le Clean Code : Principes de base

Le **Clean Code** consiste à écrire du code facile à lire, à comprendre et à maintenir. Voici les principes clés résumés :

### 1. Naming (Nommage)
*   Utilisez des noms explicites (variables, fonctions, classes).
*   Évitez les abréviations obscures.
*   *Exemple :* `fetchPostById()` au lieu de `get_p(id)`.

### 2. Fonctions et Méthodes
*   Elles doivent être courtes et ne faire qu'**une seule chose** (Single Responsibility).
*   Limitez le nombre d'arguments.

### 3. Commentaires
*   Le code doit être auto-explicatif. 
*   Utilisez des commentaires pour expliquer le "Pourquoi" (l'intention) plutôt que le "Quoi" (la syntaxe).

### 4. DRY (Don't Repeat Yourself)
*   Évitez la duplication de code. Factorisez dans des services ou des traits si nécessaire.

### 5. SOLID
*   **S**ingle Responsibility : Une classe = une seule responsabilité.
*   **O**pen/Closed : Ouvert à l'extension, fermé à la modification.
*   **L**iskov Substitution : Les sous-classes doivent pouvoir remplacer leurs classes mères.
*   **I**nterface Segregation : Préférez plusieurs petites interfaces spécifiques à une grosse généraliste.
*   **D**ependency Inversion : Dépendre des abstractions, pas des implémentations (Injection de dépendances).

### Pourquoi en Symfony ?
En suivant ces principes, vous facilitez l'évolution de votre blog et permettez à d'autres développeurs (ou à vous-même dans 6 mois) de comprendre instantanément votre logique.
