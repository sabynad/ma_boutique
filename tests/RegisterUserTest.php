<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTest extends WebTestCase
{
    public function testSomething(): void
    {


        //1. créer un faux client (navigateur) de pointer vers une URL
        $client = static::createClient();
        $client->request('GET', '/inscription');

        //2. remplir les champs de mon formulaire d'inscription 
        $client->submitForm('register_user_submit', [
            'register_user[firstname]' => 'zayneb',
            'register_user[lastname]' => 'chaf',
            'register_user[email]' => 'zayneb@mail.fr',
            'register_user[plainPassword][first]' => '123456',
            'register_user[plainPassword][second]' => '123456'
        ]);

        //FOLLOW
        $this->assertResponseRedirects('/connexion');
        $client->followRedirect();
        
        //3. peux tu regarder si dans ma page j'ai le message (alerte) suivante: Votre compteest correctement....
        $this->assertResponseIsSuccessful('div:contains("Votre compte est correctement crée, veuillez vous connecter.")');


    }
}
