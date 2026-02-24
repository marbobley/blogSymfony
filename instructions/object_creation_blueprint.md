# Blueprint : Création et Instanciation d'Objets

Ce document définit les standards pour la création d'objets (DTO, Entités, Value Objects) au sein du projet. L'objectif est de garantir l'encapsulation, la testabilité et la maintenance du code en centralisant la logique de création.

## 🏭 Pattern Factory & Builder

### Quand utiliser une Factory ou un Builder ?
- **Complexité** : L'objet nécessite plusieurs étapes d'initialisation ou des calculs pour être valide.
- **Abstraction** : Vous voulez isoler le code client de la classe concrète (ex: changer d'implémentation sans impacter les appelants).
- **Centralisation** : Plusieurs parties du code (contrôleurs, tests, commandes) créent le même type d'objet.
- **Données par défaut** : Besoin de fournir des versions pré-configurées de l'objet (ex: `createSample()` pour les tests).

### Règle d'or
Si un objet (hors simple Value Object immuable) est instancié à plus d'un endroit dans l'application, **créez une Factory ou un Builder**.

---

## 📂 Organisation des fichiers

Les factories et builders doivent être placés dans le namespace correspondant à l'objet qu'ils produisent :

- **Modèles de Domaine** : `src/Domain/Factory/`
- **Entités d'Infrastructure** : `src/Infrastructure/Factory/` (si nécessaire)

Nommage : `{NomDeLObjet}Factory` ou `{NomDeLObjet}Builder` (ex: `PostModelBuilder`).

---

## 🛠 Structure Standard d'un Builder (Exemple : PostModelBuilder)

Le projet privilégie souvent le pattern **Builder** pour les modèles de domaine afin de permettre une construction fluide (fluent interface), particulièrement utile dans les Mappers ou les tests.

```php
<?php
declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Model\PostModel;

class PostModelBuilder
{
    private PostModel $model;

    public function __construct()
    {
        $this->model = new PostModel();
    }

    public function setTitle(string $title): self
    {
        $this->model->setTitle($title);
        return $this;
    }

    public function build(): PostModel
    {
        return $this->model;
    }
}
```

---

## ⚖️ Factory/Builder vs Constructeur

| Cas d'usage | Privilégier | Pourquoi ? |
| :--- | :--- | :--- |
| **Simple Data Transfer** | Factory/Builder | Permet de faire évoluer le DTO sans casser les contrôleurs. |
| **Value Object pur** | Constructeur | Un VO est défini par ses attributs, le constructeur suffit s'il est simple. |
| **Entité complexe** | Factory/Builder | Permet de s'assurer que l'entité respecte les invariants métier dès sa naissance. |
| **Objets de test** | Factory/Builder | Centralise les "doublures" de données valides. |

---

## ✅ Checklist d'implémentation

1. [ ] Le fichier commence par `declare(strict_types=1);`.
2. [ ] La factory/builder respecte les règles Mago (ex: pas de flags booléens dans `create()`, `use function` pour les fonctions globales).
3. [ ] Le fichier est situé dans le bon dossier (`Domain/Factory` ou `Infrastructure/Factory`).
4. [ ] Pour un Builder, il contient une méthode `build()`.
5. [ ] Le code client (Controller, Mapper, Test) n'utilise plus `new {Object}` mais passe par la Factory ou le Builder.
