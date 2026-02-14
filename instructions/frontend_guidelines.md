# Directives Frontend - Blog Symfony

Ce document définit les standards pour le développement frontend de l'application, en mettant l'accent sur l'accessibilité, la simplicité et la performance.

## Principes Fondamentaux

### 1. Inclusif (Accessibilité - A11y)
*   **Contrastes** : Utiliser des couleurs contrastées pour assurer la lisibilité.
*   **Sémantique HTML** : Utiliser les balises appropriées (`<header>`, `<main>`, `<footer>`, `<article>`, `<nav>`, `<h1>`-`<h6>`).
*   **Attributs ARIA** : Ajouter des labels ARIA si nécessaire (`aria-label`, `aria-hidden`).
*   **Navigation au clavier** : S'assurer que tous les éléments interactifs sont focusables.
*   **Icônes** : Toujours accompagner une icône seule d'un texte alternatif ou d'un `aria-label`.

### 2. Mobile First & Responsive
*   **Approche** : Concevoir d'abord pour les petits écrans (sm), puis ajouter des couches pour les écrans plus larges (md, lg, xl).
*   **Grilles Bootstrap** : Utiliser le système de grille flexible (`container`, `row`, `col-12 col-md-6`).
*   **Unités Relatives** : Préférer les `rem` ou `em` aux `px` pour les polices et les espacements.
*   **Images** : Utiliser la classe `.img-fluid` pour que les images ne dépassent pas de leur conteneur.

### 3. Simple & Épuré
*   **Minimalisme** : Éviter la surcharge visuelle. Un seul message principal par écran.
*   **Hiérarchie** : Utiliser la typographie pour guider l'œil (poids, taille, couleur).
*   **Icônes** : Utiliser les `bootstrap-icons` de manière parcimonieuse pour illustrer les actions.

### 4. Animations Simples & États
*   **Discrétion** : Les animations ne doivent pas distraire l'utilisateur mais accompagner ses actions.
*   **Transitions** : Utiliser des transitions CSS fluides sur les hovers et les changements d'état (0.3s max).
*   **Performance** : Préférer `transform` et `opacity` pour les animations.
*   **Feedback** : Utiliser des messages flash pour confirmer les actions de l'utilisateur (succès, erreur).

### 5. Thémage & SEO
*   **Dark Mode** : Support natif du mode sombre via `data-bs-theme="auto"` et les variables CSS de Bootstrap 5.3.
*   **SEO de base** : Chaque page doit avoir un titre explicite et une meta description.
*   **Viewport** : Toujours inclure la balise viewport pour le responsive.

### 6. Composants Twig (Symfony UX Twig Component)
*   **Réutilisation** : Extraire tout élément HTML répétitif dans un composant (ex: `PostCard`, `TagBadge`).
*   **Composants de Structure** : Utiliser des composants pour les structures récurrentes comme les en-têtes de page (`PageHeader`) ou les tableaux d'administration (`AdminTable`).
*   **Encapsulation** : Un composant doit être autonome. Utiliser des paramètres (props) pour passer les données.
*   **Robustesse** : Toujours prévoir des valeurs par défaut (`|default`) pour les paramètres facultatifs afin d'éviter les erreurs de variable inexistante.
*   **Contenu Flexible** : Utiliser des blocs Twig (`{% block content %}{% endblock %}`) plutôt que des variables simples pour passer du HTML complexe à un composant (ex: contenu d'un tableau).

---

## Architecture de la Page

### Layout Global
Chaque page doit suivre cette structure sémantique dans `base.html.twig` :
1.  **Header** : Contenant le composant `<twig:Navbar />`.
2.  **Main** : Contenant le contenu spécifique à la page (`{% block body %}`).
3.  **Footer** : Contenant le composant `<twig:Footer />`.

---

## Mise en Pratique avec Bootstrap 5

### Structure de Base (Mobile First)
```html
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <article class="card shadow-sm border-0 animate-fade-in">
                <div class="card-body">
                    <h1 class="h3 card-title text-primary">Titre de l'Article</h1>
                    <p class="text-muted small">Publié le 06 Février 2026</p>
                    <p class="card-text">Contenu simple et lisible...</p>
                    <button class="btn btn-primary w-100 w-md-auto transition-base">
                        Lire la suite
                    </button>
                </div>
            </article>
        </div>
    </div>
</div>
```

### CSS Personnalisé (`assets/styles/app.css`)
```css
:root {
    --transition-speed: 0.3s;
}

/* Animation simple d'entrée */
.animate-fade-in {
    animation: fadeIn var(--transition-speed) ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Transition de base pour les éléments interactifs */
.transition-base {
    transition: all var(--transition-speed) ease-in-out;
}

.transition-base:hover {
    transform: translateY(-2px);
}
```

## Checklist de Validation Frontend
- [ ] Le site est-il lisible sur un écran de 320px de large ?
- [ ] Les contrastes respectent-ils les standards WCAG ?
- [ ] Le contenu est-il structuré avec des balises sémantiques (`<header>`, `<main>`, `<footer>`) ?
- [ ] Les icônes ont-elles toutes un texte alternatif ou un `aria-label` ?
- [ ] Les boutons et liens sont-ils faciles à cliquer sur mobile (taille min 44x44px) ?
- [ ] Le mode sombre est-il testé et lisible ?
- [ ] Les messages de succès/erreur sont-ils implémentés pour les actions critiques ?
- [ ] Les balises meta SEO sont-elles présentes ?
- [ ] Les animations sont-elles fluides et non envahissantes ?
