<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResetPasswordRequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Entrez votre adresse e-mail',
                'attr' => [
                    'autocomplete' => 'email',
                    'class' => 'form-control', // Assurez-vous que cela correspond à vos classes CSS pour les formulaires
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci d'indiquer votre adresse e-mail.", // Message d'erreur plus convivial
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
