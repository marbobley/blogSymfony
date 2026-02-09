<?php

namespace App\Infrastructure\Form;

use App\Domain\Enum\OgType;
use App\Domain\Enum\TwitterCard;
use App\Domain\Model\Component\SocialSeo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
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
                'mapped' => false,
            ])
            ->add('ogDescription', TextareaType::class, [
                'label' => 'Description Open Graph',
                'required' => false,
                'mapped' => false,
            ])
            ->add('ogImage', TextType::class, [
                'label' => 'Image Open Graph (URL)',
                'required' => false,
                'mapped' => false,
            ])
            ->add('ogType', EnumType::class, [
                'class' => OgType::class,
                'label' => 'Type Open Graph',
                'mapped' => false,
            ])
            ->add('twitterCard', EnumType::class, [
                'class' => TwitterCard::class,
                'label' => 'Twitter Card',
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SocialSeo::class,
            'empty_data' => fn($form) => new SocialSeo(
                ogTitle: $form->get('ogTitle')->getData(),
                ogDescription: $form->get('ogDescription')->getData(),
                ogImage: $form->get('ogImage')->getData(),
                ogType: $form->get('ogType')->getData() ?? OgType::WEBSITE,
                twitterCard: $form->get('twitterCard')->getData() ?? TwitterCard::SUMMARY_LARGE_IMAGE
            ),
            'mapped' => false,
        ]);
    }
}
