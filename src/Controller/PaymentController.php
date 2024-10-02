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

        Stripe::setApiKey('sk_test_51Q5OYhRqrTBtMpleeRxr7lubfBAQXw494dLxgiE6wqJWjFWZryUrBPEk7fx2rdM1um91OFChQdJ6VF7phyebTcR000ll3WOxgj');
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';


        $order = $orderRepository->findOneById($id_order);

        $products_for_stripe = [];

        foreach ($order->getOrderDetails() as $product) { 
            $products_for_stripe[] = [
                'price_data' => [
                  'currency' => 'eur',
                  'unit_amount' => number_format($product->getProductPriceWt() * 100, 0, '', ''),
                  'product_data' => [
                      'name' => $product->getProductName(),
                      'images' => [
                        $YOUR_DOMAIN.'/uploads/'.$product->getProductImage()
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
            'success_url' => $YOUR_DOMAIN . '/success.html',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
          ]);

        return $this->redirect($checkout_session->url);
        
    }
}
