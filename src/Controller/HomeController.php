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
       $content = "Bonjour, <br/> j'espère que cela fonctionne";
       $mail->send('mailtest@yopmail.com', 'sabri chaf', 'Bonjour, test de ma classe email', $content);

        return $this->render('home/index.html.twig');
    }
    
}
