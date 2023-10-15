<?php

namespace App\Controller;

use App\Entity\Equipes;
use App\Entity\Users;
use App\Form\EquipeFormType;
use App\Form\MembresFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EquipesController extends AbstractController
{
    #[Route('/equipes/creer_equipe', name: 'creer_equipe')]
    public function creerEquipe(Request $request, EntityManagerInterface $entityManager): Response
    {

        /** @var Users $userCourant */
        $userCourant = $this->getUser();
        dump($userCourant);
        if ($userCourant) {

            // Verifier que l'utilsiateur n'est pas déjà le capitaine d'une équipe        
            if ($userCourant->getCapitaineEquipe()) {
                // L'utilisateur est déjà capitaine d'une équipe
                return $this->redirectToRoute('app_main', [
                    'id' => $userCourant->getCapitaineEquipe()->getId(), 
                    'aDejaEquipe' => ($userCourant->getCapitaineEquipe() !== null)
                ]);
            }

            // Récuperation d'information fixe
            $sportU = $userCourant->getSportsUser();
            
            // Creation de l'équipe
            $equipe = new Equipes();
            // Le capitaine sera l'utilsiateur courant
            $equipe->setCapitaine($userCourant);
            // Creation du formulaire à partir de l'équipe
            $form = $this->createForm(EquipeFormType::class, $equipe, [
                'sport_choices' => $sportU, // Passez les choix au formulaire
            ]);
            $form->handleRequest($request);
            

            // Verification du formulaire
            if ($form->isSubmitted() && $form->isValid()) {
                
                // Modification de l'utilisateur
                $user = $entityManager->getRepository(Users::class)->find($userCourant->getId());                
                $user->setCapitaineEquipe($equipe); // Ajout de l'equipe pour indiquer qu'il est capitaine
                $user->addMembreEquipe($equipe); // Ajoute l'equipe pour indiquer que l'utilisateur fait partie de cette equipe
                $equipe->addMembre($user);

                // Migration vers la base de données
                $entityManager->persist($equipe);
                $entityManager->persist($user);
                $entityManager->flush();
                
                return $this->redirectToRoute('ajout_membre');

            }
            
            return $this->render('equipes/creer_equipe.html.twig', [
                'creerEquipeForm' => $form->createView()
            ]);
        }
        // Si pas d'utilisateur connecté tu redirige vers la page de connexion
        return $this->redirectToRoute('app_login');
    }



   

    #[Route('/equipes/mes_equipes', name: 'mes_equipes')]
    public function afficherEquipes(EntityManagerInterface $entityManager): Response
    {

        /** @var Users $userCourant */
        $userCourant = $this->getUser();
        dump($userCourant);
        if ($userCourant) {

            $em = $entityManager;

            $query = $em->createQuery('
                SELECT e
                FROM App\Entity\Equipes e
                JOIN e.membres m
                WHERE m = :user
                OR e.capitaine = :user
            ')
            ->setParameter('user', $userCourant);

            $equipes=$query->getResult();
            dump($equipes);
            
            return $this->render('equipes/mes_equipes.html.twig', [
                'equipes' => $equipes
            ]);
        }
        // Si pas d'utilisateur connecté tu redirige vers la page de connexion
        return $this->redirectToRoute('app_login');
    }



    #[Route('/equipes/ajout_membre', name: 'ajout_membre')]
    public function ajoutMembre(Request $request, EntityManagerInterface $entityManager): Response
    {

        /** @var Users $userCourant */
        $userCourant = $this->getUser();
        dump($userCourant);
        if ($userCourant) {

            //Récupération de l'équipe pour ajouter des membres
            /** @var Equipes $equipeCourante */
            $equipeCourante = $entityManager->getRepository(Equipes::class)->findOneBy(array('capitaine'=>$userCourant));                
                
            //$equipeCourante->addMembre($userCourant);
            // Creation du formulaire à partir de l'équipe
            $form = $this->createForm(MembresFormType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Récupérez les données du formulaire
                $data = $form->getData();
                $nom = $data['nom'];
                $prenom = $data['prenom'];
                $email = $data['email'];

                //Récupération du membre de l'équipe
                /** @var Users $membreAjoute */
                $membreAjoute = $entityManager->getRepository(Users::class)->findOneBy(array('nomUser'=>$nom, 'prenomUser'=>$prenom, 'email'=>$email));                
            
                $membreAjoute->addMembreEquipe($equipeCourante); // Ajoute l'equipe pour indiquer que l'utilisateur fait partie de cette equipe
                $equipeCourante->addMembre($membreAjoute); // Ajout du membre dans l'equipe courante du bon coté

                // Migration vers la base de données
                $entityManager->persist($equipeCourante);
                $entityManager->persist($membreAjoute);
                $entityManager->flush();
                

                // Si pas d'utilisateur connecté tu redirige vers la page de connexion
                return $this->redirectToRoute('ajout_membre',[
                    'succes' => true,
                    'membre' => $membreAjoute,
                ]);

            }
            
            return $this->render('equipes/ajout_membre.html.twig', [
                'ajoutMembreForm' => $form->createView(),
                'equipe' => $equipeCourante
            ]);
        }
        // Si pas d'utilisateur connecté tu redirige vers la page de connexion
        return $this->redirectToRoute('app_login');
        
    }
    
}