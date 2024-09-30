<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Form\OrderType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    // 1er etape du tunnel d'achet--------------------------------------------------------------------
    //choix de l'adresse de livraison et du transporteur 
    #[Route('/commande/livraison', name: 'app_order')]
    public function index(): Response
    {
        $addresses = $this->getUser()->getAddresses();

        if (count($addresses) == 0) {
            return $this->redirectToRoute('app_account_address_form');
        }

        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $addresses,
            'action' => $this->generateUrl('app_order_summary')
        ]);

        return $this->render('order/index.html.twig', [
            'deliverForm' => $form->createView(),
        ]);
    }

    // 2eme etape tunnel d'achat
    //  recap de la commande utilisateur 
    // Insertion en base de donnees
    // preparation du paiment vers Stripe
    #[Route('/commande/recapitulatif', name: 'app_order_summary')]
    public function add(Request $request, Cart $cart): Response
    {

        if ($request->getMethod() != 'POST') {
            return $this->redirectToRoute('app_cart');
        }

        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $this->getUser()->getAddresses(),
        
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form->getData());
            // stocker les informations en bdd
        }

        return $this->render('order/summary.html.twig', [
            'choices' => $form->getData(),
            'cart' => $cart->getCart(),
            'totalWt' => $cart->getTotalWt()
        ]);
    }


}
