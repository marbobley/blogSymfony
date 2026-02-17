# Blueprint : Création et Instanciation d'Objets

Ce document définit les standards pour la création d'objets (DTO, Entités, Value Objects) au sein du projet. L'objectif est de garantir l'encapsulation, la testabilité et la maintenance du code en centralisant la logique de création.

## 🏭 Le Pattern Factory

### Quand utiliser une Factory ?
- **Complexité** : L'objet nécessite plusieurs étapes d'initialisation ou des calculs pour être valide.
- **Abstraction** : Vous voulez isoler le code client de la classe concrète (ex: changer d'implémentation sans impacter les appelants).
- **Centralisation** : Plusieurs parties du code (contrôleurs, tests, commandes) créent le même type d'objet.
- **Données par défaut** : Besoin de fournir des versions pré-configurées de l'objet (ex: `createSample()` pour les tests).

### Règle d'or
Si un objet (hors simple Value Object immuable) est instancié à plus d'un endroit dans l'application, **créez une Factory**.

---

## 📂 Organisation des fichiers

Les factories doivent être placées dans le namespace correspondant à l'objet qu'elles produisent :

- **Modèles de Domaine** : `src/Domain/Factory/`
- **Entités d'Infrastructure** : `src/Infrastructure/Factory/` (si nécessaire)

Nommage : `{NomDeLObjet}Factory` (ex: `PostModelFactory`).

---

## 🛠 Structure Standard d'une Factory

Une factory doit être simple. Elle peut utiliser des méthodes statiques ou être un service si elle a des dépendances (ex: `PostTagSynchronizer`).

```php
<?php
declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Model\PostModel;

class PostModelFactory
{
    public static function create(string $title, string $content): PostModel
    {
        $model = new PostModel();
        $model->setTitle($title);
        $model->setContent($content);
        return $model;
    }
}
```

---

## ⚖️ Factory vs Constructeur

| Cas d'usage | Privilégier | Pourquoi ? |
| :--- | :--- | :--- |
| **Simple Data Transfer** | Factory | Permet de faire évoluer le DTO sans casser les contrôleurs. |
| **Value Object pur** | Constructeur | Un VO est défini par ses attributs, le constructeur suffit s'il est simple. |
| **Entité complexe** | Factory | Permet de s'assurer que l'entité respecte les invariants métier dès sa naissance. |
| **Objets de test** | Factory | Centralise les "doublures" de données valides. |

---

## ✅ Checklist d'implémentation

1. [ ] Le fichier commence par `declare(strict_types=1);`.
2. [ ] La factory respecte les règles Mago (ex: pas de flags booléens dans `create()`, `use function` pour les fonctions globales).
3. [ ] La factory est située dans le bon dossier (`Domain/Factory` ou `Infrastructure/Factory`).
4. [ ] Elle contient au moins une méthode `create()`.
5. [ ] Elle contient une méthode `createSample()` si l'objet est utilisé dans les tests.
6. [ ] Le code client (Controller, Use Case, Test) n'utilise plus `new {Object}` mais passe par la Factory.
