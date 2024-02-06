<?php

namespace App\Form;

use App\Entity\Diet;
use App\Entity\Restaurant;
use App\Entity\Specialty;
use App\Form\AdditionalRestauInfosType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CompleteProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('picture', TextType::class, [
                'label' => 'Lien vers la photo de profil',
                'required' => false,
            ])
            ->add('diet', EntityType::class, [
                'class' => Diet::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez un régime alimentaire',
            ])
            ->add('specialty', EntityType::class, [
                'class' => Specialty::class,
                'choice_label' => 'type',
                'placeholder' => 'Choisissez une spécialité',
            ])
            // ->add('userInfos', AdditionalUserInfosType::class)
            ->add('restauInfos', AdditionalRestauInfosType::class) // Imbriqué RestauInfosType            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Restaurant::class,
        ]);
    }
}
