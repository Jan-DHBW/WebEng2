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
    public function index(): Response
    {
        $number = random_int(0, 100);
        return $this->render('login.html.twig', [
            //'number' => $number,
        ]);
    }
}