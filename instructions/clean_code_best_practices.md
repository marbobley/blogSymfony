# Bonnes Pratiques Clean Code

Ce document répertorie les principes de conception à respecter scrupuleusement dans le projet pour garantir une base de code saine et maintenable.

## 1. Conception des Méthodes (Clean Code)

### ❌ Éviter les "Flag Arguments" (Arguments Booléens)
L'utilisation d'un paramètre booléen pour changer radicalement le comportement d'une méthode est une mauvaise pratique (règle Mago `no-boolean-flag-parameter`). Cela nuit à la lisibilité et viole souvent le principe de responsabilité unique (SRP).

**Exemple à bannir :**
```php
// Mauvais : Utilisation d'un flag booléen
public function setPublished(bool $status): void 
{
    $this->published = $status;
}
```
**Solutions :**
- Séparer en deux méthodes explicites (ex: `publish()` et `unpublish()`).
- Utiliser des objets de type `Criteria` pour les recherches complexes.

### ❌ Éviter les clauses `else`
L'utilisation de clauses `else` augmente la complexité cognitive du code (règle Mago `no-else-clause`).

**Solutions :**
- Utiliser des **clauses de garde** (guard clauses) avec des retours hâtifs.
- Utiliser l'opérateur ternaire pour les assignations simples.
- Utiliser `match` ou `switch` pour les logiques multiples.

**Exemple recommandé (Guard Clause) :**
```php
public function process(User $user): void
{
    if (!$user->isActive()) {
        return;
    }
    
    // Logique principale ici, sans indentation supplémentaire due à un else
    $user->doSomething();
}
```

### ✅ Utilisation d'un Objet Criteria / Filter
Pour éviter les signatures complexes et instables, regroupez les paramètres de recherche dans un objet dédié.

**Exemple recommandé :**
```php
final class PostCriteria
{
    public function __construct(
        private readonly ?int $tagId = null,
        private readonly ?string $search = null,
        private readonly bool $onlyPublished = false,
    ) {}
    // Getters...
}

public function execute(PostCriteria $criteria): array
```
Cet objet peut ensuite être passé de couche en couche (Use Case -> Provider -> Repository) sans modifier les signatures.

### ✅ Principe de Responsabilité Unique (SRP)
Chaque Use Case doit avoir une et une seule raison de changer. S'il contient des `if/else` majeurs sur son comportement principal, c'est probablement qu'il doit être divisé.

## 2. Architecture Hexagonale

- **Domaine Agnostique** : Le domaine ne doit jamais dépendre de l'infrastructure (pas de références à Doctrine, Symfony Request, etc.).
- **Interfaces (Ports)** : Les Use Cases communiquent avec l'extérieur via des interfaces (Providers).
- **Modèles de Domaine** : Toujours utiliser des objets du Domaine (Modèles) plutôt que des entités de base de données directement dans les Use Cases.
