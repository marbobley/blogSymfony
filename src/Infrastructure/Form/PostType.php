<?php

declare(strict_types=1);

namespace App\Infrastructure\Form;

use App\Domain\Model\PostModel;
use App\Domain\Model\TagModel;
use App\Domain\UseCaseInterface\ListTagsInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @extends AbstractType<PostModel>
 */
class PostType extends AbstractType
{
    public function __construct(
        private readonly ListTagsInterface $tags,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $tags = $this->tags->execute();
        $builder
            ->add('title', TextType::class)
            ->add('sub_title', TextareaType::class)
            ->add('content', TextareaType::class, [
                'attr' => ['rows' => 15],
            ])
            ->add('published', CheckboxType::class, [
                'label' => 'Publier immédiatement',
                'required' => false,
            ])
            ->add('tags', ChoiceType::class, [
                'choices' => $tags,
                'choice_label' => static fn(?TagModel $tag): string => $tag ? strtoupper($tag->getName()) : '',
                'choice_value' => static fn(?TagModel $tag): string => (string) $tag?->getId(),
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
            ])
            ->add('saveAndContinue', SubmitType::class, [
                'label' => 'Enregistrer et continuer l\'édition',
            ]);

        $builder->get('tags')->addModelTransformer(
            new CallbackTransformer(
                static function ($tagsAsCollection) {
                    if (null === $tagsAsCollection) {
                        return [];
                    }
                    return $tagsAsCollection->toArray();
                },
                static fn($tagsAsArray) => new ArrayCollection($tagsAsArray),
            ),
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PostModel::class,
        ]);
    }
}
