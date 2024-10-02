<?php

namespace App\Controller;


use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{
    #[Route('/commande/paiement/{id_order}', name: 'app_payment')]
    public function index($id_order, OrderRepository $orderRepository): Response
    {

        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);


        // element de securité dans l'url pour ne pas recuperer la commande d'un autre utilisateur
        $order = $orderRepository->findOneBy([
            'id' => $id_order,
            'user' => $this->getUser()
        ]);

        if (!$order) {
            return $this->redirectToRoute('app_home');
        }
        //----------------------------------------------------------------

        $products_for_stripe = [];

        foreach ($order->getOrderDetails() as $product) { 
            $products_for_stripe[] = [
                'price_data' => [
                  'currency' => 'eur',
                  'unit_amount' => number_format($product->getProductPriceWt() * 100, 0, '', ''),
                  'product_data' => [
                      'name' => $product->getProductName(),
                      'images' => [
                        $_ENV['DOMAIN'].'/uploads/'.$product->getProductImage()
                      ]
                  ]
                ],
               'quantity' => $product->getProductQuantity(),
            ];
        }

        // dd($order);
        $products_for_stripe[] = [
            'price_data' => [
              'currency' => 'eur',
              'unit_amount' => number_format($order->getCarrierPrice() * 100, 0, '', ''),
              'product_data' => [
                  'name' => 'transporteur : ' .$order->getCarrierName(),
              ]
            ],
           'quantity' => 1,
        ];
    
        // dd($products_for_stripe);

        // dd($products_for_stripe);

        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'line_items' => [[
                $products_for_stripe
            ]],
            'mode' => 'payment',
            'success_url' => $_ENV['DOMAIN'] . '/success.html',
            'cancel_url' => $_ENV['DOMAIN'] . '/cancel.html',
          ]);

        return $this->redirect($checkout_session->url);
        
    }
}
