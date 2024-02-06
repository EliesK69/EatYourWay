<?php

namespace App\Form;

use App\Entity\Diet;
use App\Entity\RestauInfos;
use App\Entity\Restaurant;
use App\Entity\Specialty;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RestaurantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('picture')
            ->add('restauInfos', EntityType::class, [
                'class' => RestauInfos::class,
'choice_label' => 'id',
            ])
            ->add('diet', EntityType::class, [
                'class' => Diet::class,
'choice_label' => 'id',
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
'choice_label' => 'id',
            ])
            ->add('specialty', EntityType::class, [
                'class' => Specialty::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Restaurant::class,
        ]);
    }
}
