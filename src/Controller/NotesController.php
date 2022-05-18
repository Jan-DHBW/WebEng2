<?php
// src/Controller/NotesController.php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NotesController extends AbstractController
{
    /**
    * @Route("/number")
    */
    public function index(): Response
    {
        return $this->render('notes.html.twig', [
            //'number' => $number,
        ]);
    }
}