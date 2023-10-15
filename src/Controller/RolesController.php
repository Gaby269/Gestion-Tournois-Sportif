<?php

namespace App\Controller;
use App\Form\RoleFormType;
use App\Entity\Roles;
use App\Entity\Users;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RolesController extends AbstractController
{
    #[Route('/roles', name: 'app_roles')]
    public function ajoutRole(Request $request, EntityManagerInterface $entityManager): Response
    {
        $role = new Roles();
        $form = $this->createForm(RoleFormType::class, $role);
        $form->handleRequest($request);

        // verification du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($role);
            $entityManager->flush();

            // si pas encore envoyer on envoie au formulaire
            return $this->render('roles/ajout_role.html.twig', [
                'rolesForm' => $form->createView(),
                'nomRole' => $role->getNomRole(),
            ]);
        }

        // si pas encore envoyer on envoie au formulaire
        return $this->render('roles/ajout_role.html.twig', [
            'rolesForm' => $form->createView(),
        ]);
    }

    #[Route('/roles/modifier_role', name: 'modif_role')]
    public function modifRole(Request $request, EntityManagerInterface $entityManager): Response
    {

        /** @var Users $userCourant */
        $userCourant = $this->getUser();

        dump($userCourant);

        if ($userCourant->getRoleUser()){
            $roles =  $userCourant->getRoleUser();
        }
        else{
            $roles = null;
        }
        dump($roles);

        $form = $this->createForm(RoleFormType::class, $roles);
        $form->handleRequest($request);

        // verification du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($roles);
            $entityManager->flush();

        }

        // si pas encore envoyer on envoie au formulaire
        return $this->render('roles/modifier_role.html.twig', [
            'rolesForm' => $form->createView(),
        ]);
    }
}