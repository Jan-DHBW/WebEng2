<?php
// src/Controller/LoginController.php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
#use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /**
    * @Route("/login")
    */
    public function login(): Response
    {
        // get the login error if there is one
        return $this->render('login.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }
}