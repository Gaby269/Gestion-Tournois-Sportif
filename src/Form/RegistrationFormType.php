<?php

namespace App\Form;
use App\Entity\Sports;
use App\Entity\Roles;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomUser')
            ->add('prenomUser')
            ->add('villeUser')
            ->add('email')
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }}caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])   
            ->add('sportsUser', EntityType::class, [ // nom de l'attribut dans Sports
                'class' => Sports::class, // quelles entités
                'choice_label' => 'nomSport', // label des options du select
                'multiple' => true, // for multiple selections
                'expanded' => true, // for checkboxes/radio buttons
                'choice_attr' => function ($choice) {
                    // Ici, nous utilisons la propriété 'nomSport' de la classe Sports
                    return ['data-custom-attr' => $choice->getNomSport()];
                },
                
                'row_attr' => ['class' => 'custom-checkbox'],
            ])
            
            ->add('roleUser', EntityType::class, [ // nom de l'attribut dans roles
                'class' => Roles::class, // quelles entités
                'choice_label' => 'nomRole', // label des options du select
                'choice_attr' => function ($choice) {
                    // Ici, nous utilisons la propriété 'nomRole' de la classe roles
                    return ['data-custom-attr' => $choice->getNomRole()];
                },
                
                'row_attr' => ['class' => 'custom-checkbox'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}