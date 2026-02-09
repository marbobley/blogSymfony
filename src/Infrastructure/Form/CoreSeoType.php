<?php

namespace App\Infrastructure\Form;

use App\Domain\Model\Component\CoreSeo;
use Symfony\Component\Form\AbstractType;
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CoreSeo::class,
        ]);
    }
}
