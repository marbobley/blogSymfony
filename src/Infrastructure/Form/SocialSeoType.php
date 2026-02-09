<?php

namespace App\Infrastructure\Form;

use App\Domain\Model\Component\SocialSeo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @extends AbstractType<SocialSeo>
 */
class SocialSeoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SocialSeo::class,
        ]);
    }
}
