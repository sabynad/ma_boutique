<?php

namespace App\Controller;

use App\Form\PasswordUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    #[Route('/compte', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }


    // modification du mon de passe
    #[Route('/compte/modifier-mot-de-passe', name: 'app_account_modify_pwd')]
    public function password(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();
        // dd($user);

        $form = $this->createForm(PasswordUserType::class, $user, [
            'passwordHasher' => $passwordHasher
        ]);

        $form->handleRequest($request); // écoute ce que l'utilisateur soumet dans les champs

        //si le formulaire est soumis alors
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush(); // met à jour la bdd
            // dd($form->getData());

            $this->addFlash(           // permet d'ajouter un message de confirmation
                'success',
                'Votre mot de passe est correctement mis à jour.'
            );

        }

        return $this->render('account/password.html.twig', [ 
            'modifyPwd' => $form->createView()
        ]);
    }


}
