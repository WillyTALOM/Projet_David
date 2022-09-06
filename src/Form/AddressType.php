<?php

namespace App\Form;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // // ->add('user', EntityType::class, [
            // //     'class' => User::class,
            // ])
            ->add('address', TextType::class)
            ->add('additional', TextType::class)
            ->add('zip')
            ->add('city', TextType::class)
            ->add('country', CountryType::class, [
                'label' => 'Pays'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
