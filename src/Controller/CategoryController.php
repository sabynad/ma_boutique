<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/categorie/{slug}', name: 'app_category')]
    public function index($slug, CategoryRepository $categoryRepository): Response
    {
        
        $category = $categoryRepository->findOneBySlug($slug);
        // dd($category);

         // reconduction vers la page home plutot que la page erreur au cas ou l'utilisateur rentre une categorie non reférencer dans l'url
        if (!$category) {
            return $this->redirectToRoute('app_home');
        }
        
        return $this->render('category/index.html.twig', [
            'category' => $category,
        ]);
    }
}
