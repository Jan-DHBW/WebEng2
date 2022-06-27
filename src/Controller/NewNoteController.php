<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Category;
use App\Entity\Note;
use App\Entity\Invitation;
use App\Form\NewNoteFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class NewNoteController extends AbstractController{
    /**
     * @Route("/newnote", name="newnote")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response{
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $categories = $user->getCategories();
        $note = new Note();
        $form = $this->createForm(NewNoteFormType::class, $note);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($note);
            $entityManager->flush();
            // do anything else you need here, like send an email
            return $this->redirectToRoute('/notes');
        }
        return $this->render('newNote.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories,
        ]);
    }
}
