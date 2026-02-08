# Risques Potentiels et Points d'Attention

Ce document répertorie les risques techniques et conceptuels identifiés lors de la mise en place de l'architecture hexagonale et du système de blog.

## 1. Désynchronisation Domaine / Infrastructure
**Risque :** Les `PostModel` (Domaine) et les entités `Post` (Infrastructure) peuvent diverger.
- **Impact :** Erreurs lors de la conversion dans les Providers, perte de données si un champ est oublié dans le mappage.
- **Atténuation :** Maintenir des tests d'intégration unitaires pour les Providers qui vérifient le mappage complet.

## 2. Complexité de la Synchronisation des Tags
**Risque :** Le `PostTagSynchronizer` gère la logique de création/suppression de relations Many-to-Many.
- **Impact :** Risque de doublons de tags en base de données si plusieurs requêtes concurrentes créent le même tag, ou suppression accidentelle de relations.
- **Atténuation :** Utiliser des contraintes d'unicité SQL sur le nom des tags et s'assurer que le synchroniseur est appelé dans une transaction Doctrine.

## 3. Performance des Providers (N+1)
**Risque :** La conversion systématique d'entités en modèles de domaine peut induire des problèmes de performance.
- **Impact :** Chargement paresseux (Lazy Loading) déclenché de manière inattendue lors de la conversion d'une collection de modèles.
- **Atténuation :** Utiliser des jointures explicites (`JOIN FETCH`) dans les repositories Doctrine utilisés par les Providers.

## 4. Fuite de l'Infrastructure dans le Domaine
**Risque :** Utiliser par inadvertance des types Doctrine (comme `ArrayCollection` ou des attributs ORM) directement dans les classes du Domaine.
- **Impact :** Violation des principes de l'architecture hexagonale, rendant le domaine dépendant d'un outil externe.
- **Atténuation :** Surveillance stricte via PHPStan et revues de code.

## 5. Gestion des Slugs
**Risque :** Le slug est généré par Doctrine (Gedmo) mais est souvent requis par le domaine pour l'affichage.
- **Impact :** Un `PostModel` nouvellement créé n'a pas de slug tant qu'il n'est pas persisté, ce qui peut poser problème pour les redirections immédiates.
- **Atténuation :** Le domaine ne doit pas compter sur le slug pour l'identification interne (utiliser l'ID ou un UUID généré par le domaine).
