<?php

namespace App\Controller;


use App\Classe\Mail;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
       $mail = new Mail();
       $mail->send('mailtest@yopmail.com', 'sabri chaf', 'Bonjour, test de ma classe email', 'mon premier email');

        return $this->render('home/index.html.twig');
    }
    
}
