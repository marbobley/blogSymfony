# TODO - Tâches Techniques

Ce document liste les améliorations techniques et les tâches d'infrastructure restantes pour le projet de blog.

## Architecture & Domaine
- [x] **Factories** : Implémenter les factories réelles pour `PostModel` et `TagModel` dans `src/Domain/Factory/`. ✓
- [x] **Validation Domaine** : Renforcer la validation interne des modèles (ex: empêcher un titre vide directement dans le modèle en plus des assertions Symfony). ✓
- [ ] **UUID** : Réfléchir à l'utilisation d'UUID générés par le domaine pour éviter la dépendance aux IDs auto-incrémentés de la base de données.

## Infrastructure
- [x] **Gestion des Exceptions** : Créer un `ExceptionListener` pour transformer les exceptions du Domaine en réponses HTTP propres (ex: `EntityNotFoundException` -> 404). ✓
- [ ] **Optimisation SQL** : Vérifier les plans d'exécution pour la récupération des posts avec leurs tags (éviter le problème N+1).
- [ ] **Sécurité** : Finaliser l'implémentation du hachage de mot de passe dans le Use Case `RegisterUser` via une interface de service.

## Tests
- [x] **Couverture Tests Unitaires** : Atteindre 100% de couverture sur le dossier `src/Domain/UseCase` et `src/Domain/Model`. ✓
- [ ] **Tests d'Intégration** : Mettre en place des tests vérifiant le bon fonctionnement des Providers avec une base de données de test (SQLite/Docker).
- [x] **Refactorisation XML** : Utilisation de données XML pour les tests unitaires via `XmlTestDataTrait`. ✓
