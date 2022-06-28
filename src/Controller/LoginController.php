<?php
// src/Controller/LoginController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
    /**
     * @Route("/", name="")
     */
    public fuction index(): RedirectResponse
     {  if($user = $this->getUser() == NULL){
        return $this->redirectToRoute('login');
        }else{
            return $this->redirectToRoute('notes');
        }
    }
