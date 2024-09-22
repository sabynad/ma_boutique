<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/mon-panier', name: 'app_cart')]
    public function index(Cart $cart): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getCart() 
        ]);
    }


    // ajout d'un produit au panier
    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add($id, Cart $cart, ProductRepository $productRepository ): Response
    {
        
        $product = $productRepository->findOneById($id);

        $cart->add($product);
        
        $this->addFlash(           // permet d'ajouter un message de confirmation
            'success',
            "Produit correctement ajouter a votre panier."
        );

        return $this->redirectToRoute('app_product', [
            'slug' => $product->getSlug()
        ]);

        // dd('produit ajouté au panier');
    }

}
