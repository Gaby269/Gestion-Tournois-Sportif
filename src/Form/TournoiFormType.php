<?php

namespace App\Form;

use App\Entity\Tournois;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TournoiFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomTournoi')
            ->add('startTime_at')
            ->add('endTime_at')
            //adre->add('dureeTournoi')
            ->add('adressePostal')
            ->add('codePostal')
            ->add('villeTournoi')
            ->add('nbEquipes')
            //->add('etatTournoi') // a remplir directement 
            ->add('typeTournoi', ChoiceType::class, [ // nom de l'attribut dans Tournoi
                'choices' => [
                    'POULE' => 'POULE',
                    'A TOUR' => 'A TOUR',
                ],
                'attr' => ['placeholder' => 'Choisissez un type']
                
            ])
            ->add('sportTournois', ChoiceType::class, [ // nom de l'attribut dans Sports
                'choices' => $options['sport_choices'], // Ajoutez une option vide
                'choice_label' => 'nomSport', // Propriété des sports à afficher
                'attr' => ['placeholder' => 'Choisissez le sport'],
                
            ])
            //->add('listeEquipes') //a rmplir plus tard
            //->add('listeGestionnaires') // a remplir plus tard et en ajoutant le gestionniare
            //->add('vainqueur') // a remplir plus tard
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tournois::class,
            'sport_choices' => [], // Tableau des choix de sport
            
        ]);
    }
}