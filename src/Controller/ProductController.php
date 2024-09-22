<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/produit/{slug}', name: 'app_product')]
    public function index($slug, ProductRepository $productRepository): Response
    {
        // dd($slug);
        $product = $productRepository->findOneBySlug($slug);

        // reconduction vers la page home plutot que la page erreur au cas ou l'utilisateur rentre un produit non reférencer dans l'url
        if (!$product) {
            return $this->redirectToRoute('app_home');
        }

        // dd($product);
        return $this->render('product/index.html.twig', [
            'product' => $product,
        ]);
    }
}
