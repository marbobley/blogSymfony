# Modélisation du Système SEO

Ce document détaille la conception du système de gestion SEO dynamique pour le blog, conformément à l'Architecture Hexagonale du projet. L'objectif est de pouvoir modifier les balises SEO (technique, social, etc.) via une base de données sans toucher au code.

## 1. Structure des Données (Modélisation)

### A. Concept de "Page SEO"
Pour identifier sur quelle page appliquer les données SEO, nous utiliserons un identifiant unique (slug ou nom de route). Pour la page d'accueil, l'identifiant sera `home`.

### B. Composants SEO (Value Objects)
Le système est découpé en composants logiques pour améliorer la cohésion et la maintenabilité.

#### 1. CoreSeo (SEO Technique)
- `title` : Le titre de la page (balise `<title>`).
- `metaDescription` : La description pour les moteurs de recherche.
- `canonicalUrl` : (Optionnel) URL canonique de la page.
- `metaRobots` : Instructions pour les robots (Enum `RobotsMode`, ex: `INDEX_FOLLOW`).

#### 2. SocialSeo (Open Graph & Twitter)
- `ogTitle` : Titre pour le partage social.
- `ogDescription` : Description pour le partage social.
- `ogImage` : URL de l'image de partage (peut être une URL externe ou un chemin interne vers `public/uploads/seo`).
- `ogType` : Type de contenu (Enum `OgType`, ex: `website`, `article`).
- `twitterCard` : Type de carte Twitter (Enum `TwitterCard`, ex: `summary_large_image`).

---

## 6. Gestion des Images
L'application permet d'uploader des images directement pour le SEO.
- Les images sont stockées dans `public/uploads/seo`.
- Le service `FileUploader` gère l'upload et la génération de noms uniques.
- Le formulaire `SocialSeoType` propose à la fois un champ texte pour une URL et un champ fichier pour l'upload.
- Le contrôleur `SeoController` traite l'upload et met à jour le modèle avant la sauvegarde.

#### 3. SitemapSeo (Indexation & Discovery)
- `inSitemap` : Booléen pour indiquer si la page doit figurer dans le sitemap XML.
- `changefreq` : Fréquence de changement (Enum `ChangeFreq`, ex: `daily`, `monthly`).
- `priority` : Priorité relative (0.0 à 1.0) pour le sitemap.

#### 4. MetaSeo (Données Additionnelles)
- `isNoIndex` : Booléen pour forcer le `noindex`.
- `schemaMarkup` : (JSON/Array) Bloc de données structurées JSON-LD.
- `breadcrumbTitle` : Titre spécifique pour le fil d'ariane.

---

## 2. Architecture Hexagonale

### 🟢 Domaine (`src/Domain`)

Les modèles de domaine sont des objets **immuables** (`readonly`).

#### 1. Modèles : `SeoModel` et ses composants
`SeoModel` est l'agrégat racine regroupant `CoreSeo`, `SocialSeo`, `SitemapSeo` et `MetaSeo`.
- **Rôle** : Transporter les données SEO de manière typée et immuable.
- **Validation** : Les contraintes métier (ex: priorité sitemap entre 0 et 1) sont validées dans les constructeurs.

#### 2. Enums
Les paramètres à choix multiples sont gérés via des Enums PHP (`RobotsMode`, `OgType`, `TwitterCard`, `ChangeFreq`).

#### 3. Provider : `SeoProviderInterface`
Interface définissant comment récupérer la SEO.
- `findByPageIdentifier(string $identifier): ?SeoModel`

### 🔴 Infrastructure (`src/Infrastructure`)

#### 1. Entité : `SeoData`
Entité Doctrine utilisant des **Embeddables** pour mapper les composants.
- **Table** : `seo_data`
- **Embeddables** : `CoreSeoData`, `SocialSeoData`, `SitemapSeoData`, `MetaSeoData`. Cela permet de garder une table plate en base de données tout en ayant des objets structurés en PHP.

#### 2. Mapper : `SeoDataMapper`
Assure la conversion entre les modèles de domaine (immuables) et les entités d'infrastructure. Il gère également la conversion entre les Enums et les chaînes de caractères pour la persistance.

#### 3. Gestion des Images
L'application permet d'uploader des images directement pour le SEO.
- **Dossier de stockage** : `public/uploads/seo`
- **Service** : `FileUploader` (gère le renommage sécurisé et le déplacement des fichiers).
- **Formulaire** : `SocialSeoType` inclut un `FileType` (non mappé) pour l'upload.

---

## 3. Formulaires Symfony

Le formulaire principal `SeoType` est découpé en sous-formulaires (`CoreSeoType`, `SocialSeoType`, etc.).
- Utilise `EnumType` pour les Enums.
- Utilise `empty_data` avec des closures pour instancier les Value Objects immuables lors de la soumission.
- Le traitement des uploads est effectué dans le **Controller** avant de passer le modèle au Use Case de sauvegarde.

---

## 4. Application à la Page d'Accueil

### Flux de données :
1. Le **Controller** de la page d'accueil injecte le `SeoProviderInterface`.
2. Il appelle `$seoProvider->findByPageIdentifier('home')`.
3. Les données SEO sont passées au template Twig.
4. Le template `home/index.html.twig` transmet ces données au bloc `head` du `base.html.twig`.

---

## 5. Évolutivité
Cette structure permet de :
1. Gérer n'importe quelle page statique via son identifiant.
2. Étendre le système pour les articles de blog via des identifiants dynamiques (ex: `post_42`).
3. Ajouter facilement de nouveaux champs dans les composants existants ou créer de nouveaux composants (ex: `TrackingSeo` pour les pixels).
