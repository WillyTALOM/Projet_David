<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\ArrayType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Rollerworks\Component\PasswordStrength\Validator\Constraints\PasswordStrength;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('first_name', TextType::class, [
                'attr' => [
                    'maxLenght' => 100
                ]
            ])
            ->add('last_name', TextType::class, [
                'attr' => [
                    'maxLenght' => 100
                ]
            ])
            ->add('phone', NumberType::class, [
                'attr' => [
                    'maxLenght' => 15
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'maxLenght' => 180
                ]
            ])
            // ->add('roles', ChoiceType::class)

            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'les deux mots de passe ne correspondent pas',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enterz a password',
                        ]),
                        // new Length([
                        //     'min' => 6,
                        //     'minMessage' => 'Your password should be at least {{ limit }} characters',
                        //     // max length allowed by Symfony for security reasons
                        //     'max' => 4096,
                        // ]),
                        new PasswordStrength([
                            'minLength' => 8,
                            'tooShortMessage' => 'Le mot de passe doit contenir au moins {{length}} caractères.',
                            'minStrength' => 4,
                            'message' => 'Le mot de passe doit contenir au moins une lettre minuscule, une lettre majiscule, un chiffre et un caratère spécial'
                        ])
                    ],

                ],
                'second_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Merci de confirmer le mot de passe',
                        ])
                    ]
                ]

            ]);
        // ->add('created_at')
        // ->add('isVerified');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
