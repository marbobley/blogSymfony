<?php

declare(strict_types=1);

namespace App\Infrastructure\Form;

use App\Application\Model\PostModel;
use App\Application\Model\TagModel;
use App\Application\UseCaseInterface\ListTagsInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @extends AbstractType<PostModel>
 */
class PostType extends AbstractType
{
    public function __construct(private readonly ListTagsInterface $tags)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $tags = $this->tags->execute();
        $builder
        ->add('title', TextType::class)
        ->add('content', TextareaType::class)
            ->add('tags', ChoiceType::class, [
                'choices' => $tags,
                'choice_label' => function (?TagModel $tag): string {
                    return $tag ? strtoupper($tag->getName()) : '';
                },
                'expanded' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PostModel::class
        ]);
    }
}
