<?php

namespace App\Infrastructure\Form;

use App\Domain\Enum\RobotsMode;
use App\Domain\Model\Component\CoreSeo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @extends AbstractType<CoreSeo>
 */
class CoreSeoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre SEO',
                'required' => false,
                'mapped' => false,
            ])
            ->add('metaDescription', TextareaType::class, [
                'label' => 'Meta Description',
                'required' => false,
                'mapped' => false,
            ])
            ->add('canonicalUrl', TextType::class, [
                'label' => 'URL Canonique',
                'required' => false,
                'mapped' => false,
            ])
            ->add('metaRobots', EnumType::class, [
                'class' => RobotsMode::class,
                'label' => 'Meta Robots',
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CoreSeo::class,
            'empty_data' => fn($form) => new CoreSeo(
                title: $form->get('title')->getData(),
                metaDescription: $form->get('metaDescription')->getData(),
                canonicalUrl: $form->get('canonicalUrl')->getData(),
                metaRobots: $form->get('metaRobots')->getData() ?? RobotsMode::INDEX_FOLLOW
            ),
            'mapped' => false,
        ]);
    }
}
