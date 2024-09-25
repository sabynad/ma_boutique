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
        $cart = $this->getCart(); 

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
    //---------



    // decrease enlever un element du panier-------------------
    public function decrease($id)
    {
        $cart = $this->getCart();

        if ($cart[$id]['qty'] > 1) {
            $cart[$id]['qty'] = $cart[$id]['qty'] - 1;
        }else {
            unset($cart[$id]);
        }

        $this->requestStack->getSession()->set('cart', $cart);
    }
    //------


    // calcule totale de la quantité de produit dans le panier-------------------
    public function fullQuantity()
    {
        $cart = $this->getCart();
        $quantity = 0;

        if (!isset($cart)) {    
            return $quantity;
        }

        foreach ( $cart as $product ) {
            $quantity = $quantity + $product['qty'];
        }

        // dd($quantity);
        return $quantity;
    }
    //------


    // calcule le prix total du panier ttc-------------------
    public function getTotalWt()
    {
        $cart = $this->getCart();
        $price = 0;

        if (!isset($cart)) {    
            return $price;
        }

        foreach ( $cart as $product ) {
            $price = $price + ($product['object']->getPriceWt() * $product['qty']);
        }

        return $price;
    }
    //--------------------------------


    // suppression total des produits dans le panier-------------------------
    public function remove()
    {
        return $this->requestStack->getSession()->remove('cart');
    }


    // fonction retournant le panier------------------------
    public function getCart() 
    {
        return $this->requestStack->getSession()->get('cart');
    }
    //------

}