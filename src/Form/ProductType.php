<?php

namespace App\Form;

use App\Entity\Sexe;
use App\Entity\Brand;

use App\Entity\Product;
use App\Form\ImageType;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'maxLenght' => 100
                ]
            ])

            ->add('abstract', TextareaType::class, [
                'attr' => [
                    'maxLength' => 65535
                ]
            ])

            ->add('description', TextareaType::class, [
                'attr' => [
                    'maxLength' => 65535
                ]
            ])

            ->add('quantity', IntegerType::class, [
                'attr' => [
                    'min' => 0,
                    'max' => 99999,
                    'step' => 1
                ]
            ])

            ->add('price', NumberType::class, [
                'attr' => [
                    'min' => 0,
                    'max' => 9999.99,
                    'step' => 0.01
                ]
            ])

            ->add('priceSolde', NumberType::class, [
                'attr' => [
                    'min' => 0,
                    'max' => 9999.99,
                    'step' => 0.01
                ]
            ])

            ->add('reduction', NumberType::class, [
                'attr' => [
                    'min' => 0,
                    'max' => 9999.99,
                    'step' => 0.01
                ]
            ])

            ->add('reference', NumberType::class, [
                'attr' => [
                    'min' => 0,
                    'max' => 9999.99,

                ]
            ])

            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'expanded' => true

            ])

            ->add('brand', EntityType::class, [
                'class' => Brand::class,
                'choice_label' => 'name',
                'expanded' => true
            ])

            ->add('sexe', EntityType::class, [
                'class' => Sexe::class,
                'choice_label' => 'name',
                'expanded' => true
            ])

            ->add('images', ImageType::class, [
                'required' => true,
                'mapped' => false,


            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
