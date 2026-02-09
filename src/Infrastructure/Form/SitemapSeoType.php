<?php

namespace App\Infrastructure\Form;

use App\Domain\Enum\ChangeFreq;
use App\Domain\Model\Component\SitemapSeo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @extends AbstractType<SitemapSeo>
 */
class SitemapSeoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('inSitemap', CheckboxType::class, [
                'label' => 'Présent dans le sitemap',
                'required' => false,
                'mapped' => false,
            ])
            ->add('changefreq', EnumType::class, [
                'class' => ChangeFreq::class,
                'label' => 'Fréquence de changement',
                'mapped' => false,
            ])
            ->add('priority', NumberType::class, [
                'label' => 'Priorité (0.0 à 1.0)',
                'required' => false,
                'scale' => 1,
                'attr' => ['min' => 0, 'max' => 1, 'step' => 0.1],
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SitemapSeo::class,
            'empty_data' => fn($form) => new SitemapSeo(
                inSitemap: $form->get('inSitemap')->getData() ?? true,
                changefreq: $form->get('changefreq')->getData() ?? ChangeFreq::WEEKLY,
                priority: (float) ($form->get('priority')->getData() ?? 0.5)
            ),
            'mapped' => false,
        ]);
    }
}
