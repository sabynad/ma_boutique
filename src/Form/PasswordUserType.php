<?php

namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class PasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('actualPassword', PasswordType::class, [
                'label' => "Votre mot de passe actuel",
                'attr' => [
                    'placeholder' => 'Entrez votre mot de passe actuel'
                ],
                'mapped' => false,  // pour pas faire le lien entre password dans l'entity et le formulaire plainPassword
            ])    

            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'max' => 30
                    ])
                ],

                'first_options'  => [
                    'label' => 'Votre nouveau mot de passe',
                    'attr' => 
                        ['placeholder' => 'Choisissez votre nouveau mot de passe'
                    ],
                    'hash_property_path' => 'password'
                ],

                'second_options' => [
                    'label' => 'Confirmez votre nouveau mot de passe',
                    'attr' => [
                        'placeholder' => "Confirmez votre nouveau mot de passe"
                    ]
                ],
                'mapped' => false,  // pour pas faire le lien entre password dans l'entity et le formulaire plainPassword
            ])

            ->add('submit', SubmitType::class, [
                'label' => "Mettre à jour mon mot de passe",
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
            
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                $form = $event->getForm(); // recuperation du formulaire
                $user = $form->getConfig()->getOptions()['data'];
                $passwordHasher = $form->getConfig()->getOptions()['passwordHasher'];
                
                // récupérer le mot de passe saisi et le comparer au mot de passe en bdd
                $isValid = $passwordHasher->isPasswordValid(
                    $user,
                    $form->get('actualPassword')->getData()
                );

                // si le code saisie est different de celui stocker en bdd
                if (!$isValid) {
                    $form->get('actualPassword')->addError(new FormError("Votre mot de passe n'est pas le bon. Veuillez vérifier votre saisie"));
                }

                

            })        
        ;
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'passwordHasher' => null,
        ]);
    }
}
