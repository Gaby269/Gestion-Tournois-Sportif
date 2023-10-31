<?php

namespace App\Controller;

use App\Entity\Users;



use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            
            return $this->render('compte/index.html.twig');
        }
        return $this->redirectToRoute('app_login');
        
    }

    #[Route('/mon_compte/modifier-nom', name: 'modifier_nom')]
    public function modifierNom(Request $request, EntityManagerInterface $entityManager)
    {
        /** @var Users $userCourant */
        $userCourant = $this->getUser();
        dump($userCourant);
        if ($userCourant) {
            $form = $this->createFormBuilder($userCourant)
                        ->add('nom_user', TextType::class, [
                            'label' => '<b>Nouveau nom : </b>',
                            'label_html' => true,
                            'attr' => [
                                'class' => 'form-control inline-input', // Ajoutez une classe CSS pour le champ de saisie
                            ],
                        ])
                        ->add('save', SubmitType::class, [
                            'label' => '<i class="material-icons">edit</i>',
                            'label_html' => true,
                            'attr' => [
                                'class' => 'bouton-modif text-center',
                            ],
                        ])
                        ->getForm();
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $nouveauNom = $form->get('nom_user')->getData();
                $userCourant->setNomUser($nouveauNom);

                // Envoie à la bdd
                $entityManager->persist($userCourant);
                $entityManager->flush();
        
                return $this->redirectToRoute('app_compte');

            }

            return $this->render('compte/index.html.twig', [
                'formModification' => $form->createView(),
                'nomVisible' => true
            ]);
        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('/mon_compte/modifier-prenom', name: 'modifier_prenom')]
    public function modifierPrenom(Request $request, EntityManagerInterface $entityManager)
    {
        /** @var Users $userCourant */
        $userCourant = $this->getUser();
        dump($userCourant);
        if ($userCourant) {
            $form = $this->createFormBuilder($userCourant)
                        ->add('prenom_user', TextType::class, [
                            'label' => '<b>Nouveau prenom : </b>',
                            'label_html' => true,
                            'attr' => [
                                'class' => 'form-control inline-input', // Ajoutez une classe CSS pour le champ de saisie
                            ],
                        ])
                        ->add('save', SubmitType::class, [
                            'label' => '<i class="material-icons">edit</i>',
                            'label_html' => true,
                            'attr' => [
                                'class' => 'bouton-modif text-center',
                            ],
                        ])
                        ->getForm();
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $nouveauPrenom = $form->get('prenom_user')->getData();
                $userCourant->setPrenomUser($nouveauPrenom);

                // Envoie à la bdd
                $entityManager->persist($userCourant);
                $entityManager->flush();
        
                return $this->redirectToRoute('app_compte');

            }

            return $this->render('compte/index.html.twig', [
                'formModification' => $form->createView(),
                'prenomVisible' => true
            ]);
        }
        return $this->redirectToRoute('app_login');
    }


    #[Route('/mon_compte/modifier-ville', name: 'modifier_ville')]
    public function modifierVille(Request $request, EntityManagerInterface $entityManager)
    {
        /** @var Users $userCourant */
        $userCourant = $this->getUser();
        dump($userCourant);
        if ($userCourant) {
            $form = $this->createFormBuilder($userCourant)
                        ->add('ville_user', TextType::class, [
                            'label' => '<b>Nouvelle ville : </b>',
                            'label_html' => true,
                            'attr' => [
                                'class' => 'form-control inline-input', // Ajoutez une classe CSS pour le champ de saisie
                            ],
                        ])
                        ->add('save', SubmitType::class, [
                            'label' => '<i class="material-icons">edit</i>',
                            'label_html' => true,
                            'attr' => [
                                'class' => 'bouton-modif text-center',
                                'type' => 'submit'
                            ],
                        ])
                        ->getForm();
            $form->handleRequest($request);

            dump("coucou1");
            if ($form->isSubmitted() && $form->isValid()) {
                
                dump("coucou");
                $nouvelleVille = $form->get('ville_user')->getData();
                $userCourant->setVilleUser($nouvelleVille);

                // Envoie à la bdd
                $entityManager->persist($userCourant);
                $entityManager->flush();
        
                return $this->redirectToRoute('app_compte');

            }

            return $this->render('compte/index.html.twig', [
                'formModification' => $form->createView(),
                'villeVisible' => true
            ]);
        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('/mon_compte/modifier-email', name: 'modifier_email')]
    public function modifierEmail(Request $request, EntityManagerInterface $entityManager)
    {
        /** @var Users $userCourant */
        $userCourant = $this->getUser();
        dump($userCourant);
        if ($userCourant) {
            $form = $this->createFormBuilder($userCourant)
                        ->add('email', EmailType::class, [
                            'label' => '<b>Nouvel email : </b>',
                            'label_html' => true,
                            'attr' => [
                                'class' => 'form-control inline-input', // Ajoutez une classe CSS pour le champ de saisie
                            ],
                        ])
                        ->add('save', SubmitType::class, [
                            'label' => '<i class="material-icons">edit</i>',
                            'label_html' => true,
                            'attr' => [
                                'class' => 'bouton-modif text-center',
                            ],
                        ])
                        ->getForm();
            $form->handleRequest($request);
            dump("coucou1");
            if ($form->isSubmitted() && $form->isValid()) {
                dump("coucou");
                $nouveauEmail = $form->get('email')->getData();
                $userCourant->setEmail($nouveauEmail);

                // Envoie à la bdd
                $entityManager->persist($userCourant);
                $entityManager->flush();
        
                return $this->redirectToRoute('app_compte');

            }

            return $this->render('compte/index.html.twig', [
                'formModification' => $form->createView(),
                'emailVisible' => true
            ]);
        }
        return $this->redirectToRoute('app_login');
    }
}