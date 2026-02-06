# Guidelines PHPStan - Blog Symfony

Ce document définit les standards d'analyse statique pour garantir la robustesse et la fiabilité du code.

## 1. Niveau d'Analyse
*   Le projet cible le **Niveau 8** de PHPStan (sur une échelle de 10).
*   Ce niveau exige une gestion rigoureuse des types `null`, des types de retour et des types de propriétés.

## 2. Configuration (`phpstan.neon`)
*   **Paths** : Les dossiers `src` et `tests` doivent être analysés.
*   **Symfony** : L'extension `phpstan-symfony` est obligatoire pour analyser correctement les conteneurs de services et les contrôleurs.
*   **Doctrine** : L'extension `phpstan-doctrine` est utilisée pour valider les entités et les requêtes DQL.

## 3. Règles d'Or
1.  **Zéro Erreur** : Aucune nouvelle PR ne doit être acceptée si elle introduit des erreurs PHPStan.
2.  **Types Itérables** : Tout tableau ou collection doit être documenté via PHPDoc pour préciser le type des éléments.
    *   *Exemple :* `/** @return Post[] */`
3.  **Gestion du Null** : Ne jamais ignorer un type potentiellement `null`. Utilisez des vérifications explicites ou des garde-fous.
4.  **Pas d'Ignorance Facile** : L'utilisation de `// @phpstan-ignore-line` ou des `ignoreErrors` dans la config doit rester exceptionnelle et justifiée par un commentaire.

## 4. Workflow de Développement
*   **Analyse locale** : Lancez PHPStan régulièrement pendant le développement :
    ```bash
    vendor/bin/phpstan analyse
    ```
*   **Correction immédiate** : Si PHPStan signale une erreur sur du code métier (Domaine), c'est souvent le signe d'un manque de clarté dans la logique. Préférez refactoriser plutôt que d'ajouter des PHPDoc complexes.

## 5. Spécificités Architecture Hexagonale
*   **Interfaces** : PHPStan aide à vérifier que les implémentations dans `Infrastructure` respectent parfaitement les contrats définis dans `Domain` et `Application`.
*   **DTOs** : Les propriétés `readonly` doivent être correctement typées pour que PHPStan puisse garantir leur immutabilité.
