# Guide : Formulaires Symfony avec Objets Immuables (Readonly)

Ce guide documente les leçons apprises lors de l'implémentation du système SEO utilisant des modèles de domaine `final readonly` (immuables) avec les formulaires Symfony.

## 1. Problématique

Symfony est conçu par défaut pour travailler avec des objets **mutables** (utilisation de getters et setters). Lorsqu'un modèle de domaine est déclaré en `readonly` :
- Il n'a pas de setters.
- Il doit être instancié via son constructeur avec toutes ses données.
- Symfony ne peut pas "mapper" automatiquement les données soumises sur un objet existant.

## 2. Configuration du Formulaire

Pour gérer ces objets, les configurations suivantes sont nécessaires dans le `AbstractType` :

### A. Désactivation du mapping automatique
Chaque champ doit avoir l'option `'mapped' => false`. Cela empêche Symfony d'essayer d'appeler un setter sur l'objet de données lors de la soumission.

```php
$builder->add('title', TextType::class, [
    'mapped' => false,
    'data' => $options['data']?->getTitle(), // Population manuelle (voir section 3)
]);
```

### B. Utilisation de `empty_data` pour l'instanciation
L'option `empty_data` doit être utilisée pour définir comment créer une nouvelle instance de l'objet immuable à partir des données du formulaire.

```php
public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'data_class' => CoreSeo::class,
        'empty_data' => function (FormInterface $form) {
            return new CoreSeo(
                title: $form->get('title')->getData(),
                metaDescription: $form->get('metaDescription')->getData(),
                // ...
            );
        },
    ]);
}
```

## 3. Population des données à l'édition (Update)

Comme `'mapped' => false` est utilisé, Symfony ne remplit pas automatiquement les champs du formulaire à partir de l'objet initial lors d'une édition. Il faut donc le faire manuellement dans `buildForm` :

```php
public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $data = $options['data'] ?? null;

    $builder->add('title', TextType::class, [
        'mapped' => false,
        'data' => $data?->getTitle(),
    ]);
}
```

## 4. Limites et Points d'attention (Leçons Apprises)

### Le piège de la mise à jour (Update)
L'option `empty_data` de Symfony n'est déclenchée que si les données initiales du formulaire sont `null`. Si vous passez un objet existant au formulaire (`$this->createForm(MyType::class, $existingObject)`), Symfony considère que l'objet est déjà là et **ne fera pas appel à `empty_data`**.

**Conséquence :** `form->getData()` retournera toujours l'objet initial (non modifié) car Symfony n'a pas pu y écrire les nouvelles valeurs (faute de setters) et n'a pas créé de nouvel objet (car `empty_data` a été sauté).

### Solutions recommandées :

1.  **Re-instanciation manuelle dans le Controller (Approche actuelle) :**
    Si `form->getData()` ne retourne pas les données mises à jour, il faut extraire les données du formulaire manuellement et créer une nouvelle instance du modèle avant de la passer au Use Case.

    ```php
    if ($form->isSubmitted() && $form->isValid()) {
        $model = new MyReadonlyModel(
            field: $form->get('field')->getData()
        );
        $saveUseCase->execute($model);
    }
    ```

2.  **Usage d'un DataMapper (Approche "Pro") :**
    Pour une intégration plus propre, implémenter `Symfony\Component\Form\DataMapperInterface`. Cela permet de contrôler exactement comment les données sont lues de l'objet immuable vers le formulaire, et comment un **nouvel** objet est créé à partir des données du formulaire lors de la soumission.

3.  **DTO Mutables :**
    Une alternative courante est d'utiliser un DTO (Data Transfer Object) classique avec des propriétés publiques ou des setters pour le formulaire, puis de convertir ce DTO en objet de domaine immuable après validation.

## 5. Cas des Uploads de fichiers
Pour les champs `FileType` dans un modèle immuable, il est préférable de :
1. Déclarer le champ en `mapped => false` dans le formulaire.
2. Gérer l'upload dans le Controller ou un service dédié.
3. Passer le chemin final du fichier (string) au constructeur du modèle de domaine.
