<?php

namespace App\Controller;
use App\Form\SportFormType;
use App\Entity\Sports;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SportsController extends AbstractController
{
    #[Route('/sports', name: 'app_sports')]
    public function ajoutSport(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sport = new Sports();
        $form = $this->createForm(SportFormType::class, $sport);
        $form->handleRequest($request);

        // verification du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sport);
            $entityManager->flush();

            // si pas encore envoyer on envoie au formulaire
            return $this->render('sports/ajout_sport.html.twig', [
                'sportsForm' => $form->createView(),
                'nomSport' => $sport->getNomSport(),
            ]);

        }

        // si pas encore envoyer on envoie au formulaire
        return $this->render('sports/ajout_sport.html.twig', [
            'sportsForm' => $form->createView(),
        ]);
    }
}