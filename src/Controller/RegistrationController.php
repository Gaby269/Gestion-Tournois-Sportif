<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Roles;
use App\Form\RegistrationFormType;
use App\Security\UsersAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UsersAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        // Recuperation de la requete du mdp hasher, interface user authentification, et entité manager pour acceder aux entité
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // verification du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // hasher le mdp
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            // Récupérer les roles selectionner
            $selectedRole = $form->get('roleUser')->getData();
            dump($selectedRole);

            // Assurez-vous que l'entité Roles existe pour éviter une exception
            if ($selectedRole) {

                // Chercher l'id du role
                /** @var Roles $role */
                $role = $entityManager->getRepository(Roles::class)->findOneBy(['nomRole' => $selectedRole->getNomRole()]);                
                dump($role);
                $user->setRoleUser($role);

                // Persistez à nouveau l'entité utilisateur pour mettre à jour le rôle
                $entityManager->persist($user);
                $entityManager->flush();
            }
        
            // on authentifie l'utilisateur ensuite
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        // si pas encore envoyer on envoie au formulaire
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}