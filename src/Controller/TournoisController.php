<?php

namespace App\Controller;

use App\Entity\Tournois;
use App\Entity\Users;
use App\Form\TournoiFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TournoisController extends AbstractController
{
    #[Route('/tournois/creer_tournoi', name: 'creer_tournoi')]
    public function creerTournoi(Request $request, EntityManagerInterface $entityManager): Response
    {

        /** @var Users $userCourant */
        $userCourant = $this->getUser();
        dump($userCourant);
        if ($userCourant) {

            // Récuperation d'information fixe
            $sportU = $userCourant->getSportsUser();
            dump($sportU);
            
            // Creation du tournoi
            $tournoi = new Tournois();
            // Creation du formulaire à partir du tounoi
            $form = $this->createForm(TournoiFormType::class, $tournoi, [
                'sport_choices' => $sportU
            ]);
            $form->handleRequest($request);
            

            // Verification du formulaire
            if ($form->isSubmitted() && $form->isValid()) {

                // Calcul de la durée du tournoi
                $dateDebut = new \DateTime($form->get('startTime_at')->getData()->format('Y-m-d H:i:s'));
                $dateFin = new \DateTime($form->get('endTime_at')->getData()->format('Y-m-d H:i:s'));
                // Calcul de la durée en jours
                $diff = $dateDebut->diff($dateFin);
                $dureeEnJours = $diff->days;
                
                // Modification des paramètress
                $tournoi->setDureeTournoi($dureeEnJours); // on ajoute la duree du tournoi
                // TODO remplacer par si la date de commencement est passé ou pas
                $tournoi->setEtatTournoi('PREVU'); // on dit que le tournoi n'a pas encore commencer
                $tournoi->addListeGestionnaire($userCourant); // ajoute le gestionnaire actuel
                
                // Migration vers la base de données
                $entityManager->persist($tournoi);
                $entityManager->flush();

                return $this->redirectToRoute('mes_tournois');
                
            }
            
            return $this->render('tournois/creer_tournoi.html.twig', [
                'creerTournoiForm' => $form->createView()
            ]);
        
        }
        return $this->redirectToRoute('app_login');
    }


    #[Route('/tournois/mes_tournois', name: 'mes_tournois')]
    public function afficherTournois(EntityManagerInterface $entityManager): Response
    {

        /** @var Users $userCourant */
        $userCourant = $this->getUser();
        dump($userCourant);
        if ($userCourant) {

            $em = $entityManager;

            $query = $em->createQuery('
                SELECT e
                FROM App\Entity\Tournois e
                JOIN e.listeGestionnaires g
                WHERE g = :user
            ')
            ->setParameter('user', $userCourant);

            $tournois=$query->getResult();
            dump($tournois);
            
            return $this->render('tournois/mes_tournois.html.twig', [
                'tournois' => $tournois
            ]);
        }
        // Si pas d'utilisateur connecté tu redirige vers la page de connexion
        return $this->redirectToRoute('app_login');
    }

    #[Route('/tournois/inscription_tournois', name: 'inscription_tournois')]
    public function sinscrireTournois(Request $request, EntityManagerInterface $entityManager): Response
    {

        /** @var Users $userCourant */
        $userCourant = $this->getUser();
        dump($userCourant);
        if ($userCourant) {

             
            $query = $entityManager->createQuery('
                SELECT t
                FROM App\Entity\Tournois t
                WHERE t.sportTournois = :sport
            ')
            ->setParameter('sport', $userCourant->getSportsUser()); // Remplacez "getSport" par la méthode appropriée pour obtenir le sport de l'utilisateur

            $tournois = $query->getResult();
            dump($tournois);

            // Verification du formulaire
            if (isset($_POST['inscription_button'])) {
                
                $tournoiId = $request->request->get('tournoi_id');
                    
                /** @var Tournois $tournoi */
                $tournois = $entityManager->find(Tournois::class, $tournoiId);                
                dump($tournois);

                return $this->redirectToRoute('liste_tournois', [
                    'inscritTournoi' => $tournois
                ]);

                
            };
            
            return $this->render('tournois/liste_tournois.html.twig', [
                'tournois' => $tournois,
            ]);
        }
        // Si pas d'utilisateur connecté tu redirige vers la page de connexion
        return $this->redirectToRoute('app_login');
    }
}