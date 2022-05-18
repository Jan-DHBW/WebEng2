<?php
// src/Controller/LoginController.php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LoginController extends AbstractController
{
    /**
    * @Route("/login")
    */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

            // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        
        return $this->render('login.html.twig', [
            #'error' => $error,
        ]);
    }
}