# Système de Partage d'Articles

Ce document décrit le fonctionnement et l'extensibilité du système de partage d'articles mis en place sur le blog.

## 1. Architecture

Le système repose sur le principe **Open/Closed** de SOLID, utilisant le mécanisme de tagging de services de Symfony.

### A. Interface du Domaine
Le contrat est défini par `App\Domain\Service\Sharing\SharingServiceInterface`.
Chaque service de partage doit implémenter deux méthodes :
- `getShareUrl(string $url, string $title): string` : Génère l'URL de partage spécifique à la plateforme.
- `getPlatformName(): string` : Retourne le nom de la plateforme (ex: "Facebook").

### B. Infrastructure (Services)
Les implémentations concrètes se trouvent dans `src/Infrastructure/Service/Sharing/`.
Actuellement implémentés :
- `FacebookSharingService`
- `RedditSharingService`
- `BlueskySharingService`
- `LinkedInSharingService`

### C. Composant Twig
Le composant `ShareButtons` (`src/Infrastructure/Twig/Component/ShareButtons.php`) reçoit automatiquement tous les services implémentant l'interface via un `!tagged_iterator` configuré dans `config/services.yaml`.

## 2. Comment ajouter une nouvelle plateforme de partage ?

1.  **Créer le Service** : Créez une nouvelle classe dans `src/Infrastructure/Service/Sharing/` qui implémente `SharingServiceInterface`.
2.  **Tagging Automatique** : Grâce à la configuration `_instanceof` dans `config/services.yaml`, votre nouveau service sera automatiquement marqué avec le tag `app.sharing_service`.
3.  **Affichage** : Le bouton apparaîtra automatiquement dans le composant de partage sur la page de détail des articles, sans aucune modification supplémentaire.
4.  **Icône** : Assurez-vous que le nom retourné par `getPlatformName()` (en minuscules) correspond à une icône Bootstrap Icons (utilisée dans le template `ShareButtons.html.twig`).

## 3. Stratégie de Test

Chaque nouveau service de partage doit être accompagné de deux types de tests :

### A. Test Unitaire
Vérifie la logique pure de génération d'URL.
Exemple : `tests/Unit/Infrastructure/Service/Sharing/FacebookSharingServiceTest.php`

### B. Test d'Intégration
Vérifie que le service est correctement injecté dans la page et que le lien rendu est valide.
Exemple : `tests/Integration/Controller/FacebookShareIntegrationTest.php`
*Note : Nous séparons les tests d'intégration par plateforme pour une meilleure isolation.*

## 4. Configuration Symfony (Rappel)
Extrait de `config/services.yaml` :
```yaml
    App\Infrastructure\Twig\Component\ShareButtons:
        arguments:
            $sharingServices: !tagged_iterator app.sharing_service

    _instanceof:
        App\Domain\Service\Sharing\SharingServiceInterface:
            tags: ['app.sharing_service']
```
