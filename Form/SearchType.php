<?php

namespace App\Form;

use App\Entity\Diet;
use App\Entity\RestauInfos;
use App\Entity\Restaurant;
use App\Entity\Specialty;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
             ->add('name', null, [
                 'attr' => [
                    'placeholder' => 'Recherche ...',
              ],
            ])
            ->add('restauInfos', EntityType::class, [
                'class' => RestauInfos::class,
                    'choice_label' => 'city',
            ])
            ->add('diet', EntityType::class, [
                'class' => Diet::class,
                    'choice_label' => 'name',
            ])
            ->add('specialty', EntityType::class, [
                'class' => Specialty::class,
                    'choice_label' => 'type',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Restaurant::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
    
}
