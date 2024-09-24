<?php 

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{

    public function __construct(private RequestStack $requestStack)
    {

    }
    
    public function add($product)
    {
        // appeler la session cart de symfony
         $cart = $this->requestStack->getSession()->get('cart'); // permet de pas ecraser l'article du panier en cours

        // Ajouter une quantité +1 à mon produit condition si ajoute deux fois le meme produit la quantité une nouvelle fois de +1
        // si dans le panier le produit existe déjà
        if (isset($cart[$product->getId()])) { 
            $cart[$product->getId()] = [ 
                'object' => $product,
                'qty' => $cart[$product->getId()]['qty'] + 1
            ];
        } else { 
            $cart[$product->getId()] = [ 
                'object' => $product,
                'qty' => 1
            ];
        }

        // Créer ma session Cart
        $this->requestStack->getSession()->set('cart', $cart); 

        // dd($this->requestStack->getSession()->get('cart'));
    }

    // suppression des produits panier
    public function remove()
    {
        return $this->requestStack->getSession()->remove('cart');
    }

    public function getCart() 
    {
        return $this->requestStack->getSession()->get('cart');
    }

}