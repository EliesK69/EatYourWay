<?php

namespace App\Form;

use App\Entity\Restaurant;
use App\Entity\User;
use App\Entity\UserInfos;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('password')
            ->add('crea_date')
            ->add('mod_date')
            ->add('role')
            ->add('userInfos', EntityType::class, [
                'class' => UserInfos::class,
'choice_label' => 'id',
            ])
            ->add('restaurant', EntityType::class, [
                'class' => Restaurant::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
