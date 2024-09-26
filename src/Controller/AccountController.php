<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressUserType;
use App\Form\PasswordUserType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
 

    // affichage du compte-------------------------------------------------------------------------
    #[Route('/compte', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }


    // modification du mon de passe-----------------------------------------------------------------
    #[Route('/compte/modifier-mot-de-passe', name: 'app_account_modify_pwd')]
    public function password(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {

        $user = $this->getUser();
        // dd($user);

        $form = $this->createForm(PasswordUserType::class, $user, [
            'passwordHasher' => $passwordHasher
        ]);

        $form->handleRequest($request); // écoute ce que l'utilisateur soumet dans les champs

        //si le formulaire est soumis alors
        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->flush(); // met à jour la bdd
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

    // affichages des adresses dans le compte-----------------------------------------------------------
    #[Route('/compte/adresses', name: 'app_account_addresses')]
    public function addresses(): Response
    {
        return $this->render('account/addresses.html.twig');
    }


    // Supprimer une adresse-----------------------------------------------------------------------------
    #[Route('/compte/adresses/delete/{id}', name: 'app_account_address_delete')]
    public function addressDelete($id, AddressRepository $addressRepository): Response
    {
        $address = $addressRepository->findOneById($id);

            if (!$address OR $address->getUser() != $this->getUser()) {
                return $this->redirectToRoute('app_account_addresses');
            }
        $this->addFlash(
            type: 'success',
            message: "Votre adresse à bien été supprimée."
        );

        $this->entityManager->remove($address);
        $this->entityManager->flush();  // exécute réellement la suppression dans la base de données.

        return $this->redirectToRoute('app_account_addresses');
    }


    // Formulaire ajout adresses ------------------------------------------------------------------------
    #[Route('/compte/adresse/ajouter/{id}', name: 'app_account_address_form', defaults: ['id' => null] )]
    public function addressForm(Request $request, $id, AddressRepository $addressRepository): Response
    {
        if ($id) {
            $address = $addressRepository->findOneById($id);
            if (!$address OR $address->getUser() != $this->getUser()) {
                return $this->redirect('app_account_addresses');
            }

        } else {
            $address = new Address();
            $address->setUser($this->getUser());
        }

        
        $form = $this->createForm(AddressUserType::class, $address);

        $form = $form->handleRequest($request);
        
       if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($address); // on persiste lorsque c'est une creation d'objet
            $this->entityManager->flush();

            $this->addFlash(
                type: 'success',
                message: "Votre adresse est correctement sauvegardée."
            );
            return $this->redirectToRoute('app_account_addresses'); // redirection vers la page de mes adresses si tout se passe bien. ici, tu peux aussi rediriger vers une page de confirmation ou une page de redirection spécifique.
        }

        return $this->render('account/addressForm.html.twig', [
            'addressForm' => $form
        ]);
    }


}
