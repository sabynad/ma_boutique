<?php

namespace App\Controller\Account;

use App\Entity\Address;
use App\Form\AddressUserType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AddressController extends AbstractController
{

    // permet de discuter avec doctrine et recuperer les data en bdd
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    

    // affichages des adresses dans le compte-----------------------------------------------------------
    #[Route('/compte/adresses', name: 'app_account_addresses')]
    public function index(): Response
    {
        return $this->render('account/address/index.html.twig');
    }


    // Supprimer une adresse-----------------------------------------------------------------------------
    #[Route('/compte/adresses/delete/{id}', name: 'app_account_address_delete')]
    public function delete($id, AddressRepository $addressRepository): Response
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
    public function form(Request $request, $id, AddressRepository $addressRepository): Response
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

        return $this->render('account/address/form.html.twig', [
            'addressForm' => $form
        ]);
    }

}

