<?php

namespace App\Controller;

use App\Entity\Users;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class CompteController extends AbstractController
{
    #[Route('/mon_compte', name: 'app_compte')]
    public function afficherCompte(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {

        /** @var Users $userCourant */
        $userCourant = $this->getUser();
        dump($userCourant);
        if ($userCourant) {
            

            $form = $this->createFormBuilder();
            
            // Modifier chaque élément 
            if (isset($_POST['modifier-nom'])) {
                
                // Récupérer le nouveau nom depuis le formulaire
                $nouveauNom = $request->request->get('nouveau_nom');
                // Modification du nom
                $userCourant->setNomUser($nouveauNom);

                // Enregistrer les modifications dans la base de données
                $entityManager->persist($userCourant);
                $entityManager->flush();

                return $this->redirectToRoute('/mon_compte');
                
            };
            if (isset($_POST['modifier-prenom'])) {
                
                // Récupérer le nouveau nom depuis le formulaire
                $nouveauPrenom = $request->request->get('nouveau_prenom');
                // Modification du nom
                $userCourant->setPrenomUser($nouveauPrenom);

                // Enregistrer les modifications dans la base de données
                $entityManager->persist($userCourant);
                $entityManager->flush();

                return $this->redirectToRoute('/mon_compte');
                
            };
            if (isset($_POST['modifier-ville'])) {
                
                // Récupérer le nouveau nom depuis le formulaire
                $nouveauVille = $request->request->get('nouveau_ville');
                // Modification du nom
                $userCourant->setVilleUser($nouveauVille);

                // Enregistrer les modifications dans la base de données
                $entityManager->persist($userCourant);
                $entityManager->flush();

                return $this->redirectToRoute('/mon_compte');
                
            };

            if (isset($_POST['modifier-email'])) {
                $form = $this->createFormBuilder($userCourant)
                    ->add('nouveau_email', EmailType::class, [
                        'label' => 'Nouvel e-mail', // Étiquette du champ
                    ])
                    ->add('save', SubmitType::class, [
                        'label' => 'Enregistrer', // Étiquette du bouton
                    ])
                    ->getForm(); 
            
                    
                $form->handleRequest($request);
            
                if ($form->isSubmitted() && $form->isValid()) {
                    $nouveauEmail = $form->get('nouveau_email')->getData();
                    $userCourant->setEmail($nouveauEmail);

                    // Envoie à la bdd
                    $entityManager->persist($userCourant);
                    $entityManager->flush();
            
                    return $this->redirectToRoute('mon_compte');
                }
            }
            

            if (isset($_POST['modifier-mdp'])) {
                
                // Récupérer le nouveau nom depuis le formulaire
                $nouveauMdp = $request->request->get('nouveau_mdp');
                // Modification du nom
                $userCourant->setPassword(
                    $userPasswordHasher->hashPassword(
                    $userCourant,
                    $nouveauMdp
                ));

                // Enregistrer les modifications dans la base de données
                $entityManager->persist($userCourant);
                $entityManager->flush();

               
                
            };

            return $this->render('compte/index.html.twig', [
                'formModification' => $form
            ]);
            
        }
        return $this->redirectToRoute('app_login');
        
    }
}