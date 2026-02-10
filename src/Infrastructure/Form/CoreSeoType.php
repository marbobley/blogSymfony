<?php

namespace App\Infrastructure\Form;

use App\Domain\Enum\RobotsMode;
use App\Domain\Model\Component\CoreSeo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

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
                'required' => true,
            ])
            ->add('metaDescription', TextareaType::class, [
                'label' => 'Meta Description',
                'required' => false,
            ])
            ->add('canonicalUrl', TextType::class, [
                'label' => 'URL Canonique',
                'required' => false,
            ])
            ->add('favicon', TextType::class, [
                'label' => 'Favicon (URL)',
                'required' => false,
                'help' => 'Laissez vide si vous uploadez un fichier ci-dessous',
            ])
            ->add('faviconFile', FileType::class, [
                'label' => 'Favicon (Fichier)',
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '1M',
                        'mimeTypes' => [
                            'image/x-icon',
                            'image/png',
                            'image/jpeg',
                            'image/svg+xml',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (ICO, PNG, JPG, SVG)',
                    ])
                ],
            ])
            ->add('metaRobots', EnumType::class, [
                'class' => RobotsMode::class,
                'label' => 'Meta Robots',
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
                favicon: $form->get('favicon')->getData(),
                metaRobots: $form->get('metaRobots')->getData() ?? RobotsMode::INDEX_FOLLOW
            ),
            'mapped' => false,
        ]);
    }
}
