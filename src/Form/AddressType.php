<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address', TextType::class,[
                'attr' => [
                    'maxLenght' => 255
                ]
            ])
            ->add('additional', TextType::class,[
                'attr' => [
                    'maxLenght' => 255
                ]
            ])
            ->add('zip', IntegerType::class, [
                'attr' => [
                    'maxLenght' => 5
                ]
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'maxLenght' => 100
                ]
            ])
            ->add('country', CountryType::class, [
                'attr' => [
                    'maxLenght' => 100
                ]
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btn-block btn-info'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
