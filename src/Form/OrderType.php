<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reference')
            ->add('amount')
            ->add('created_at')
            ->add('paid')
            ->add('delivery_address', TextType::class)
            ->add('billing_address', TextType::class)
            ->add('carrier', EntityType::class, [
                'class' => Carrier::class,
                'choice_label' => function (Carrier $carrier) {
                    return $carrier->getName() . ' (' . number_format($carrier->getPrice(), 2, ',', '') . ' €)';
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
