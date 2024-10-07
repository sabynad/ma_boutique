<?php

namespace App\Controller;

use id;
use App\Classe\Cart;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/mon-panier/{motif}', name: 'app_cart', defaults: [ 'motif' => null ])]
    public function index(Cart $cart, $motif): Response
    {
        if ($motif == "annulation") {
            $this->addFlash(
                'info',
                "Paiement annulé : vous pouvez mettre à jour votre panier et votre commande."
            );
        }
        
        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getCart(), 
            'totalWt' => $cart->getTotalWt()
        ]);
    }


    // ajout d'un produit au panier
    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add($id, Cart $cart, ProductRepository $productRepository, Request $request ): Response
    {
        // dd($request->headers->get('referer'));
        
        $product = $productRepository->findOneById($id);

        $cart->add($product);
        
        $this->addFlash(           // permet d'ajouter un message de confirmation
            'success',
            "Produit correctement ajouter a votre panier."
        );

        return $this->redirect($request->headers->get('referer')); // ajoute un produit avec le + et redirige au panier

        // dd('produit ajouté au panier');
    }
    

    // diminue un produit du panier
    #[Route('/cart/decrease/{id}', name: 'app_cart_decrease')]
    public function decrease($id, Cart $cart): Response
    {

        $cart->decrease($id);
        
        $this->addFlash(           // permet d'ajouter un message de confirmation
            'success',
            "Produit correctement supprimée de votre panier."
        );

        return $this->redirectToRoute('app_cart'); // redirige vers le panier

        // dd('produit ajouté au panier');
    }


    // Supprimer les produits du panier
    #[Route('/cart/remove', name: 'app_cart_remove')]
    public function remove(Cart $cart): Response
    {
        
        $cart->remove();
        
        return $this->redirectToRoute('app_home',);
        
    }

}
