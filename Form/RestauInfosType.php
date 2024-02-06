<?php

namespace App\Form;

use App\Entity\RestauInfos;
use App\Entity\Restaurant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RestauInfosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('phone')
            ->add('street_number')
            ->add('street')
            ->add('zip_code')
            ->add('city')
            ->add('opening_date')
            ->add('mod_date')
            ->add('siret')
            ->add('restaurant', EntityType::class, [
                'class' => Restaurant::class,
'choice_label' => 'id',
            ])
            ->add('restau', EntityType::class, [
                'class' => Restaurant::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RestauInfos::class,
        ]);
    }
}
