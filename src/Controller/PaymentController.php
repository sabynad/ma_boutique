<?php

namespace App\Controller;


use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{
    #[Route('/commande/paiement', name: 'app_payment')]
    public function index(): Response
    {
        Stripe::setApiKey('sk_test_51Q5OYhRqrTBtMpleeRxr7lubfBAQXw494dLxgiE6wqJWjFWZryUrBPEk7fx2rdM1um91OFChQdJ6VF7phyebTcR000ll3WOxgj');
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $checkout_session = Session::create([
            'line_items' => [[
              # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
              'price_data' => [
                  'currency' => 'eur',
                  'unit_amount' => '1500',
                  'product_data' => [
                      'name' => 'produit test'
                  ]
              
              ],
              'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success.html',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
          ]);

        return $this->redirect($checkout_session->url);
        
    }
}
