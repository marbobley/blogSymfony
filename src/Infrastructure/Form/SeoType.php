<?php

declare(strict_types=1);

namespace App\Infrastructure\Form;

use App\Domain\Model\SeoModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @extends AbstractType<SeoModel>
 */
class SeoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pageIdentifier', TextType::class, [
                'label' => 'Identifiant de la page (ex: app_home)',
                'disabled' => $options['is_edit'],
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre SEO',
                'required' => false,
            ])
            ->add('metaDescription', TextareaType::class, [
                'label' => 'Meta Description',
                'required' => false,
            ])
            ->add('canonicalUrl', TextType::class, [
                'label' => 'URL Canonique',
                'required' => false,
            ])
            ->add('metaRobots', TextType::class, [
                'label' => 'Meta Robots',
                'required' => false,
                'attr' => ['placeholder' => 'index, follow'],
            ])
            ->add('ogTitle', TextType::class, [
                'label' => 'Titre Open Graph',
                'required' => false,
            ])
            ->add('ogDescription', TextareaType::class, [
                'label' => 'Description Open Graph',
                'required' => false,
            ])
            ->add('ogImage', TextType::class, [
                'label' => 'Image Open Graph (URL)',
                'required' => false,
            ])
            ->add('ogType', TextType::class, [
                'label' => 'Type Open Graph',
                'required' => false,
                'attr' => ['placeholder' => 'website'],
            ])
            ->add('twitterCard', ChoiceType::class, [
                'label' => 'Twitter Card',
                'choices' => [
                    'Summary' => 'summary',
                    'Summary Large Image' => 'summary_large_image',
                ],
                'required' => false,
            ])
            ->add('inSitemap', CheckboxType::class, [
                'label' => 'Présent dans le sitemap',
                'required' => false,
            ])
            ->add('isNoIndex', CheckboxType::class, [
                'label' => 'No Index',
                'required' => false,
            ])
            ->add('changefreq', ChoiceType::class, [
                'label' => 'Fréquence de changement',
                'choices' => [
                    'Always' => 'always',
                    'Hourly' => 'hourly',
                    'Daily' => 'daily',
                    'Weekly' => 'weekly',
                    'Monthly' => 'monthly',
                    'Yearly' => 'yearly',
                    'Never' => 'never',
                ],
                'required' => false,
            ])
            ->add('priority', NumberType::class, [
                'label' => 'Priorité (0.0 à 1.0)',
                'required' => false,
                'scale' => 1,
                'attr' => ['min' => 0, 'max' => 1, 'step' => 0.1],
            ])
            ->add('schemaMarkup', TextareaType::class, [
                'label' => 'Schema Markup (JSON-LD)',
                'required' => false,
            ])
            ->add('breadcrumbTitle', TextType::class, [
                'label' => 'Titre Breadcrumb',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SeoModel::class,
            'is_edit' => false,
            'empty_data' => fn($form) => new SeoModel(
                pageIdentifier: $form->get('pageIdentifier')->getData() ?? '',
            ),
        ]);
    }
}
