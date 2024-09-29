<?php

namespace App\Controller;

use App\Form\OrderType;
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
            'addresses' => $addresses
        ]);

        return $this->render('order/index.html.twig', [
            'deliverForm' => $form->createView(),
        ]);
    }
}
