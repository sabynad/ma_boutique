<?php

namespace App\Twig;

use App\Classe\Cart;
use Twig\TwigFilter;
use Twig\Extension\GlobalsInterface;
use Twig\Extension\AbstractExtension;
use App\Repository\CategoryRepository;

class AppExtensions extends AbstractExtension implements GlobalsInterface
{

    private $categoryRepository;
    private $cart;

    public function __construct(CategoryRepository $categoryRepository, Cart $cart)
    {
        $this->categoryRepository = $categoryRepository;
        $this->cart = $cart;
    }


    // création d'une extension 'price' pour filtrer les prix ex: deux chiffres apres virgule aussi virgule au lieu du point ect..
    public function getFilters()
    {
       return [
            new TwigFilter('price', [$this, 'formatPrice'])
       ]; 
    }


    public function formatPrice($number)
    {
        return number_format($number, '2', decimal_separator: ','). ' €';
    }
    //----------------------------------------------------------------


    // creation  d'une extension qui va retourner tte les categorie peut importe l'endroit ou l'on est sur le site
    public function getGlobals(): array
    {
        return [
            'allCategories' => $this->categoryRepository->findAll(),
            'fullCartQuantity' => $this->cart->fullQuantity()
        ];
    }
    //---------------------------------------------------------------- 




}