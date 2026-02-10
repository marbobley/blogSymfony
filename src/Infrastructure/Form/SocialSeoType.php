<?php

namespace App\Infrastructure\Form;

use App\Domain\Enum\OgType;
use App\Domain\Enum\TwitterCard;
use App\Domain\Model\Component\SocialSeo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

/**
 * @extends AbstractType<SocialSeo>
 */
class SocialSeoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var SocialSeo|null $data */
        $data = $options['data'] ?? null;

        $builder
            ->add('ogTitle', TextType::class, [
                'label' => 'Titre Open Graph',
                'required' => false,
                'mapped' => false,
                'data' => $data?->getOgTitle(),
            ])
            ->add('ogDescription', TextareaType::class, [
                'label' => 'Description Open Graph',
                'required' => false,
                'mapped' => false,
                'data' => $data?->getOgDescription(),
            ])
            ->add('ogImage', TextType::class, [
                'label' => 'Image Open Graph (URL)',
                'required' => false,
                'mapped' => false,
                'help' => 'Laissez vide si vous uploadez une image ci-dessous',
                'data' => $data?->getOgImage(),
            ])
            ->add('ogImageFile', FileType::class, [
                'label' => 'Image Open Graph (Fichier)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (JPG, PNG, WEBP)',
                    ])
                ],
            ])
            ->add('ogType', EnumType::class, [
                'class' => OgType::class,
                'label' => 'Type Open Graph',
                'mapped' => false,
                'data' => $data?->getOgType(),
            ])
            ->add('twitterCard', EnumType::class, [
                'class' => TwitterCard::class,
                'label' => 'Twitter Card',
                'mapped' => false,
                'data' => $data?->getTwitterCard(),
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
