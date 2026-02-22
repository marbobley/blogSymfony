<?php

declare(strict_types=1);

namespace App\Infrastructure\Form;

use App\Infrastructure\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @extends AbstractType
 */
class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('email', EmailType::class, [
            'label' => 'Email',
            'attr' => [
                'placeholder' => 'votre@email.com',
                'class' => 'transition-base',
            ],
            'constraints' => [
                new NotBlank(message: 'L\'email est obligatoire.'),
                new Email(message: 'L\'adresse email n\'est pas valide.'),
            ],
        ])->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options' => [
                'label' => 'Mot de passe',
                'attr' => [
                    'placeholder' => 'Choisissez un mot de passe robuste',
                    'class' => 'transition-base',
                ],
            ],
            'second_options' => [
                'label' => 'Confirmez le mot de passe',
                'attr' => [
                    'placeholder' => 'Répétez votre mot de passe',
                    'class' => 'transition-base',
                ],
            ],
            'invalid_message' => 'Les mots de passe doivent être identiques.',
            'constraints' => [
                new NotBlank(message: 'Le mot de passe est obligatoire.'),
                new Length(
                    min: 8,
                    max: 4096,
                    minMessage: 'Le mot de passe doit faire au moins {{ limit }} caractères.',
                ),
            ],
        ]);
    }

    /**
     * @throws AccessException
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'empty_data' => static function (FormInterface $form) {
                /** @var string|null $email */
                $email = $form->get('email')->getData();
                /** @var string|null $password */
                $password = $form->get('password')->getData();
                return new User((string) $email, (string) $password);
            },
        ]);
    }
}
