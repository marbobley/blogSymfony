<?php

namespace App\Infrastructure\Form;

use App\Domain\Model\Component\MetaSeo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @extends AbstractType<MetaSeo>
 */
class MetaSeoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isNoIndex', CheckboxType::class, [
                'label' => 'No Index',
                'required' => false,
                'mapped' => false,
            ])
            ->add('breadcrumbTitle', TextType::class, [
                'label' => 'Titre Breadcrumb',
                'required' => false,
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MetaSeo::class,
            'empty_data' => fn($form) => new MetaSeo(
                isNoIndex: $form->get('isNoIndex')->getData() ?? false,
                breadcrumbTitle: $form->get('breadcrumbTitle')->getData()
            ),
            'mapped' => false,
        ]);
    }
}
