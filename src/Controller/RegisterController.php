<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();

        $form = $this->createForm(RegisterUserType::class, $user);

        $form->handleRequest($request); // écoute ce que l'utilisateur soumet dans les champs

        //si le formulaire est soumis alors
        if ($form->isSubmitted() && $form->isValid()) {

            // dd($form->getData());
            $entityManager->persist($user); //prépare l'objet $user à être inséré dans la base de données (persist lorsque new user)
            $entityManager->flush(); //exécute réellement l'insertion ou la mise à jour dans la base de données.

            $this->addFlash(           // permet d'ajouter un message de confirmation
                'success',
                'Votre compte est correctement crée, veuillez vous connectez.'
            );
            return $this->redirectToRoute('app_login');  // redirection vers la page de login si tout se passe bien. ici, tu peux aussi rediriger vers une page de confirmation ou une page de redirection spécifique.
            
        }

        // tu enregistre les datas en bdd
        // tu envoies un message de confirmation du compte bien crée

        return $this->render('register/index.html.twig', [
            'registerForm' => $form->createView(),
        ]);
    }
}
