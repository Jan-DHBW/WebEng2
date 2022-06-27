<?php
// src/Controller/NotesController.php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Note;
use App\Entity\Invitaion;


class NotesController extends AbstractController
{
    /**
    * @Route("/notes", name="notes")
    */
    public function index(ManagerRegistry $doctrine, int $uid): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $
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