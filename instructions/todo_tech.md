# TODO - Tâches Techniques

Ce document liste les améliorations techniques et les tâches d'infrastructure restantes pour le projet de blog.

## Clean code 
1.- [ ] **Pas de valeur par défaut dans les paramètres** : Notamment dans les factories (ex: `PostModelFactory`).
2.- [ ] **Pas de boolean dans les paramètres sauf si c'est une valeur d'un objet** : si le boolean est utilisé pour un if préférer faire 2 méthodes distincts.


## Architecture & Domaine
- [x] **Factories** : Implémenter les factories réelles pour `PostModel` et `TagModel` dans `src/Domain/Factory/`. ✓
- [x] **Validation Domaine** : Renforcer la validation interne des modèles (ex: empêcher un titre vide directement dans le modèle en plus des assertions Symfony). ✓
- [x] **Initialisation des Modèles** : Initialiser les propriétés typées pour éviter les notices PHPUnit. ✓
- [ ] **UUID** : Réfléchir à l'utilisation d'UUID générés par le domaine pour éviter la dépendance aux IDs auto-incrémentés de la base de données.

## Infrastructure
- [x] **Gestion des Exceptions** : Créer un `ExceptionListener` pour transformer les exceptions du Domaine en réponses HTTP propres (ex: `EntityNotFoundException` -> 404). ✓
- [ ] **Optimisation SQL** : Vérifier les plans d'exécution pour la récupération des posts avec leurs tags (éviter le problème N+1).
- [x] **Sécurité : Hachage des mots de passe** : Déplacer le hachage du mot de passe hors du contrôleur. Utiliser une interface de service injectée dans le Use Case `RegisterUser` pour respecter l'architecture hexagonale. ✓

## Tests
- [x] **Couverture Tests Unitaires** : Atteindre 100% de couverture sur le dossier `src/Domain/UseCase` et `src/Domain/Model`. ✓
- [x] **Tests d'Intégration** : Mettre en place des tests d'intégration pour tous les Use Cases du Domaine. ✓ Voir `tests/Integration/UseCase/`.
- [x] **Refactorisation XML** : Utilisation de données XML pour les tests unitaires via `XmlTestDataTrait`. ✓
