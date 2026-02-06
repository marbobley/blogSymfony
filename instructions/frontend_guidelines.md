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

### 4. Animations Simples
*   **Discrétion** : Les animations ne doivent pas distraire l'utilisateur mais accompagner ses actions.
*   **Transitions** : Utiliser des transitions CSS fluides sur les hovers et les changements d'état (0.3s max).
*   **Performance** : Préférer `transform` et `opacity` pour les animations.

---

## Architecture de la Page

### Layout Global
Chaque page doit suivre cette structure sémantique dans `base.html.twig` :
1.  **Header** : Contenant la navigation principale (`<nav>`).
2.  **Main** : Contenant le contenu spécifique à la page (`{% block body %}`).
3.  **Footer** : Contenant les liens secondaires et les informations de copyright.

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
- [ ] Les animations sont-elles fluides et non envahissantes ?
