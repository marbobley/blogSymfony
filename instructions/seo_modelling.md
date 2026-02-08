# Modélisation du Système SEO

Ce document détaille la conception du système de gestion SEO dynamique pour le blog, conformément à l'Architecture Hexagonale du projet. L'objectif est de pouvoir modifier les balises SEO (technique, social, etc.) via une base de données sans toucher au code.

## 1. Structure des Données (Modélisation)

### A. Concept de "Page SEO"
Pour identifier sur quelle page appliquer les données SEO, nous utiliserons un identifiant unique (slug ou nom de route). Pour la page d'accueil, l'identifiant sera `home`.

### B. Champs SEO (Propriétés)
Le système doit supporter au minimum :

**SEO Technique :**
- `title` : Le titre de la page (balise `<title>`).
- `metaDescription` : La description pour les moteurs de recherche.
- `canonicalUrl` : (Optionnel) URL canonique de la page.
- `metaRobots` : Instructions pour les robots (ex: `index, follow`).

**SEO Social (Open Graph & Twitter) :**
- `ogTitle` : Titre pour le partage social.
- `ogDescription` : Description pour le partage social.
- `ogImage` : URL de l'image de partage.
- `ogType` : Type de contenu (ex: `website`, `article`).
- `twitterCard` : Type de carte Twitter (ex: `summary_large_image`).

**Indexation & Discovery :**
- `inSitemap` : Booléen pour indiquer si la page doit figurer dans le sitemap XML.
- `changefreq` : Fréquence de changement (ex: `daily`, `monthly`) pour le sitemap.
- `priority` : Priorité relative (0.0 à 1.0) pour le sitemap.
- `isNoIndex` : Booléen pour forcer le `noindex` indépendamment de `metaRobots`.

**Données Structurées & Rich Snippets :**
- `schemaMarkup` : (JSON) Bloc de données structurées JSON-LD (ex: `WebSite`, `Organization`, `Article`).
- `breadcrumbTitle` : Titre spécifique pour le fil d'ariane (souvent plus court que le `title`).

---

## 2. Architecture Hexagonale

### 🟢 Domaine (`src/Domain`)

#### 1. Modèle : `SeoModel`
Un objet simple contenant toutes les propriétés citées plus haut.
- **Rôle** : Transporter les données SEO de manière typée.
- **Validation** : Doit vérifier la longueur des titres et descriptions (ex: max 60 chars pour title, 160 pour description).

#### 2. Provider : `SeoProviderInterface`
Interface définissant comment récupérer la SEO.
- `findByPageIdentifier(string $identifier): ?SeoModel`

### 🔴 Infrastructure (`src/Infrastructure`)

#### 1. Entité : `SeoData`
Entité Doctrine mappée sur la table `seo_data`.
- **Table** : `seo_data`
- **Colonnes supplémentaires** : `page_identifier` (string, unique, indexé).

#### 2. Persistance : `DoctrineSeoProvider`
Implémente `SeoProviderInterface`. Il interroge la table `seo_data` et convertit l'entité en `SeoModel`.

---

## 3. Application à la Page d'Accueil

### Flux de données :
1. Le **Controller** de la page d'accueil injecte le `SeoProviderInterface`.
2. Il appelle `$seoProvider->findByPageIdentifier('home')`.
3. Les données SEO sont passées au template Twig.
4. Le template `home/index.html.twig` transmet ces données au bloc `head` du `base.html.twig`.

### Exemple de structure SQL :
```sql
CREATE TABLE seo_data (
    id INT AUTO_INCREMENT NOT NULL,
    page_identifier VARCHAR(255) NOT NULL, -- ex: 'home'
    title VARCHAR(255) DEFAULT NULL,
    meta_description VARCHAR(255) DEFAULT NULL,
    canonical_url VARCHAR(255) DEFAULT NULL,
    meta_robots VARCHAR(50) DEFAULT 'index, follow',
    og_title VARCHAR(255) DEFAULT NULL,
    og_description VARCHAR(255) DEFAULT NULL,
    og_image VARCHAR(255) DEFAULT NULL,
    og_type VARCHAR(50) DEFAULT 'website',
    twitter_card VARCHAR(50) DEFAULT 'summary_large_image',
    in_sitemap BOOLEAN DEFAULT TRUE,
    changefreq VARCHAR(20) DEFAULT 'weekly',
    priority NUMERIC(2, 1) DEFAULT 0.5,
    is_no_index BOOLEAN DEFAULT FALSE,
    schema_markup JSON DEFAULT NULL,
    breadcrumb_title VARCHAR(255) DEFAULT NULL,
    UNIQUE INDEX UNIQ_SEO_PAGE (page_identifier),
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
```

---

## 4. Gestion du Schema.org (JSON-LD)
L'ajout du champ `schema_markup` permet d'injecter des données structurées spécifiques. 
- **Pour la Home** : On pourra y mettre le type `WebSite` avec les informations sur le blog et la barre de recherche interne (Sitelinks Searchbox).
- **Validation** : Le `SeoModel` devra s'assurer que le contenu est un JSON valide avant de l'envoyer au template.

---

## 5. Gestion du Sitemap
La présence des champs `inSitemap`, `changefreq` et `priority` permet de générer dynamiquement un fichier `sitemap.xml`.
- Un service dédié (ex: `SitemapGenerator`) pourra boucler sur les entrées de `seo_data` ayant `inSitemap = true`.
- Pour les articles de blog, le générateur pourra combiner les données de la table `post` avec des valeurs par défaut ou des surcharges SEO si elles existent.

---

## 6. Évolutivité
Bien que nous nous concentrions sur la page d'accueil, cette structure permet de :
1. Gérer n'importe quelle page statique via son identifiant.
2. Étendre le système plus tard pour les articles de blog (soit via un identifiant dynamique `post_42`, soit en liant l'entité `Post` à une entrée `SeoData`).
