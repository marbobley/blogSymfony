# Bonnes Pratiques Clean Code

Ce document répertorie les principes de conception à respecter scrupuleusement dans le projet pour garantir une base de code saine et maintenable.

## 1. Conception des Méthodes (Clean Code)

### ❌ Éviter les "Flag Arguments" (Arguments Booléens)
L'utilisation d'un paramètre booléen pour changer radicalement le comportement d'une méthode est une mauvaise pratique. Cela nuit à la lisibilité et viole souvent le principe de responsabilité unique (SRP).

**Exemple à bannir :**
```php
public function execute(bool $onlyPublished = true): array 
{
    if ($onlyPublished) {
        return $this->provider->findPublished();
    }
    return $this->provider->findAll();
}
```
**Solutions :**
- Séparer en deux méthodes distinctes.
- Créer deux Use Cases différents (ex: `ListPublishedPosts` et `ListAllPosts`).

### ❌ Éviter les Signatures Télescopiques
Une méthode avec trop de paramètres optionnels devient difficile à appeler et à lire.

**Exemple problématique :**
```php
public function execute(?int $tagId = null, bool $onlyPublished = true, ?string $search = null): array
```
**Solutions :**
- Utiliser un **Objet Criteria / Filter** (Data Transfer Object) pour regrouper les paramètres de recherche.
- Utiliser des arguments nommés (PHP 8+) pour la clarté, mais cela ne règle pas la complexité de la méthode.

### ✅ Principe de Responsabilité Unique (SRP)
Chaque Use Case doit avoir une et une seule raison de changer. S'il contient des `if/else` majeurs sur son comportement principal, c'est probablement qu'il doit être divisé.

## 2. Architecture Hexagonale

- **Domaine Agnostique** : Le domaine ne doit jamais dépendre de l'infrastructure (pas de références à Doctrine, Symfony Request, etc.).
- **Interfaces (Ports)** : Les Use Cases communiquent avec l'extérieur via des interfaces (Providers).
- **Modèles de Domaine** : Toujours utiliser des objets du Domaine (Modèles) plutôt que des entités de base de données directement dans les Use Cases.
