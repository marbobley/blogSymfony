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
        /** @var CoreSeo|null $data */
        $data = $options['data'] ?? null;

        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre SEO',
                'required' => true,
                'mapped' => false,
                'data' => $data?->getTitle(),
            ])
            ->add('metaDescription', TextareaType::class, [
                'label' => 'Meta Description',
                'required' => false,
                'mapped' => false,
                'data' => $data?->getMetaDescription(),
            ])
            ->add('canonicalUrl', TextType::class, [
                'label' => 'URL Canonique',
                'required' => false,
                'mapped' => false,
                'data' => $data?->getCanonicalUrl(),
            ])
            ->add('favicon', TextType::class, [
                'label' => 'Favicon (URL)',
                'required' => false,
                'help' => 'Laissez vide si vous uploadez un fichier ci-dessous',
                'mapped' => false,
                'data' => $data?->getFavicon(),
            ])
            ->add('faviconFile', FileType::class, [
                'label' => 'Favicon (Fichier)',
                'required' => false,
                'mapped' => false,
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
                'mapped' => false,
                'data' => $data?->getMetaRobots(),
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
                metaRobots: $form->get('metaRobots')->getData() ?? RobotsMode::INDEX_FOLLOW,
                faviconFile: $form->get('faviconFile')->getData()
            ),
            'mapped' => false,
        ]);
    }
}
