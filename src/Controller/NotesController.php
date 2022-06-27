<?php
// src/Controller/NotesController.php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NotesController extends AbstractController
{
    /**
    * @Route("/notes")
    */
    public function index(): Response
    {
        $notes = [
            'Categorie 1' => [
                'Note 1',
                'Note 2',
                'Note 3',
            ],
            'Categorie 2' => [
                'Note 4',
                'Note 5',
                'Note 6',
            ],
        ];
        return $this->render('notes.html.twig', [
            'notes' => $notes,
        ]);
    }
    // ajax post request to sync notes
    /**
    * @Route("/notes/sync")
    */
    public function sync(Request $request): Response
    {
        // return the request as json
        return $this->json($request->request->all());
    }
}