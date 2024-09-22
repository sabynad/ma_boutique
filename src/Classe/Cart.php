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
        // appeler la session de symfony
         $cart = $this->requestStack->getSession()->get('cart'); // permet de pas ecraser l'article du panier en cours

        // Ajouter une quantité +1 à mon produit condition si ajoute deux fois le meme produit la quantité une nouvelle fois de +1
        if ($cart[$product->getId()]) { // si dans le panier le produit existe déjà
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

        //Créer ma session Cart
        $this->requestStack->getSession()->set('cart', $cart); 

        // dd($this->requestStack->getSession()->get('cart'));
    }

    public function getCart() 
    {
        return $this->requestStack->getSession()->get('cart');
    }

}