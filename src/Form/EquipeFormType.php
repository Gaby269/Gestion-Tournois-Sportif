<?php

namespace App\Form;

use App\Entity\Equipes;
use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Core\Security;

class EquipeFormType extends AbstractType
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Récuperation de l'utilsiateur courant
        /** @var Users $userCourant */
        $userCourant = $this->security->getUser(); // Récupérez l'utilisateur actuellement authentifié
        
        // Récueprer la liste des sports
        $listeSport = $userCourant->getSportsUser();
        dump($listeSport);


        $builder
            ->add('nomEquipe')
            ->add('niveau')
            ->add('adresseMail')
            ->add('numeroTel')
            ->add('sport', ChoiceType::class, [ // nom de l'attribut dans Sports
                'choices' => $options['sport_choices'], // Ajoutez une option vide
                'choice_label' => 'nomSport', // Propriété des sports à afficher
                'multiple' => false,
                'expanded' => false, // Pour un choix unique (non multiple)
                'attr' => ['placeholder' => 'Choisissez le sport'],
                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipes::class,
            'sport_choices' => [], // Tableau des choix de sport
        ]);
    }
}