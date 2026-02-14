# Injection de Services par Tags (Symfony)

Ce document explique comment utiliser les tags et les itérateurs taggués dans Symfony pour créer des systèmes extensibles, comme le système de partage d'articles.

## 1. Le concept

Le mécanisme repose sur deux étapes clés dans la configuration du conteneur de services (`config/services.yaml`) : le **marquage (Tagging)** et l'**injection de la collection (Tagged Iterator)**.

## 2. Configuration dans `services.yaml`

### A. Marquage automatique avec `_instanceof`

Pour regrouper tous les services d'un certain type sans les déclarer un par un, on utilise la section `_instanceof`.

```yaml
    _instanceof:
        App\Domain\Service\Sharing\SharingServiceInterface:
            tags: ['app.sharing_service']
```

*   **Action** : Symfony détecte automatiquement toute classe qui implémente `SharingServiceInterface`.
*   **Résultat** : Il lui attribue l'étiquette (le tag) `app.sharing_service`.
*   **Avantage** : Si vous créez un `LinkedInSharingService`, Symfony lui mettra le tag tout seul dès qu'il verra l'interface.

### B. Injection avec `!tagged_iterator`

Pour recevoir tous ces services marqués dans une classe (par exemple, un composant Twig), on utilise l'instruction `!tagged_iterator`.

```yaml
    App\Infrastructure\Twig\Component\ShareButtons:
        arguments:
            $sharingServices: !tagged_iterator app.sharing_service
```

*   **`!tagged_iterator`** : Demande à Symfony de chercher tous les services ayant le tag spécifié.
*   **Injection** : Il les fournit sous forme d'une collection itérable (`iterable`) au constructeur.

## 3. Implémentation PHP

Dans la classe qui reçoit les services, le constructeur doit accepter un type `iterable` :

```php
final class ShareButtons
{
    /**
     * @param iterable<SharingServiceInterface> $sharingServices
     */
    public function __construct(
        private readonly iterable $sharingServices,
    ) {
    }

    public function getLinks(): array
    {
        foreach ($this->sharingServices as $service) {
            // Utilisation des services...
        }
    }
}
```

## 4. Avantages de cette approche

1.  **Extensibilité (Open/Closed Principle)** : Vous pouvez ajouter de nouvelles plateformes de partage simplement en créant une nouvelle classe.
2.  **Zéro Configuration Additionnelle** : Pas besoin de modifier le composant Twig ou d'ajouter manuellement le nouveau service dans `services.yaml`.
3.  **Découplage** : Le composant ne connaît pas les implémentations concrètes, il manipule seulement une collection d'interfaces.
