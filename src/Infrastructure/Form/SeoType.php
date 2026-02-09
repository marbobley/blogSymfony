<?php

declare(strict_types=1);

namespace App\Infrastructure\Form;

use App\Domain\Model\Component\CoreSeo;
use App\Domain\Model\Component\MetaSeo;
use App\Domain\Model\Component\SitemapSeo;
use App\Domain\Model\Component\SocialSeo;
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
                'mapped' => false,
            ]);

        // Grouper les champs par composant pour le modèle de données
        $builder->add('core', CoreSeoType::class, ['label' => false, 'mapped' => false]);
        $builder->add('social', SocialSeoType::class, ['label' => false, 'mapped' => false]);
        $builder->add('sitemap', SitemapSeoType::class, ['label' => false, 'mapped' => false]);
        $builder->add('meta', MetaSeoType::class, ['label' => false, 'mapped' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SeoModel::class,
            'is_edit' => false,
            'empty_data' => function ($form) {
                return new SeoModel(
                    pageIdentifier: $form->get('pageIdentifier')->getData() ?? '',
                    core: $form->get('core')->getData() ?? new CoreSeo(),
                    social: $form->get('social')->getData() ?? new SocialSeo(),
                    sitemap: $form->get('sitemap')->getData() ?? new SitemapSeo(),
                    meta: $form->get('meta')->getData() ?? new MetaSeo()
                );
            },
        ]);
    }
}
