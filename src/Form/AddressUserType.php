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

class AddressUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom',
                'attr' => ['placeholder' => 'Entrez votre prénom'],
            ])

            ->add('lastname', TextType::class, [
                'label' => 'Votre Nom',
                'attr' => ['placeholder' => 'Entrez votre nom'],
            ])

            ->add('address' , TextType::class, [
                'label' => 'Votre adresse',
                'attr' => ['placeholder' => 'Entrez votre adresse'],
            ])
            
            ->add('postal', TextType::class, [
                'label' => 'Votre code postal',
                'attr' => ['placeholder' => 'Entrez votre code postal'],
            ])

            ->add('city', TextType::class, [
                'label' => 'Votre ville',
                'attr' => ['placeholder' => 'Entrez votre ville'],
            ])
            ->add('country', CountryType::class, [
                'label' => 'Votre pays',
                'attr' => ['placeholder' => 'Entrez votre pays'],
            ])

            ->add('phone' , TextType::class, [
                'label' => 'Votre téléphone',
                'attr' => ['placeholder' => 'Indiquez votre numéro de téléphone'],
            ])

            ->add('submit', SubmitType::class, [
                'label' => "Sauvegarder",
                'attr' => [
                    'class' => 'btn btn-success'
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
