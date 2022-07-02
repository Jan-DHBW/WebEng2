<?php
// src/Controller/NotesController.php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use App\Form\NewNoteFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\RegistrationFormType;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Note;
use App\Entity\moveType;
use App\Entity\Invitaion;
use App\Entity\moveTask;
use App\Entity\deleteTask;
use App\Form\NewCatFormType;
use App\Form\MoveNoteFormType;
use App\Form\DeleteNoteFormType;



class NotesController extends AbstractController
{
    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    /**
    * @Route("/notes", name="notes")
    */
    public function index(EntityManagerInterface $entityManager,Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $allnotes = $user->getNotes();

        $usercategories = $user->getCategories();
        $notes = array();
        $uncatnotes = array();
        foreach($user->getNotes() as $tmpnote){
            if($tmpnote->getCategory() == NULL){
                array_push($uncatnotes, $tmpnote);
            }
        }
        $notes['Unsoriert']= $uncatnotes;
        foreach($usercategories as $tmpcategory){
            $catname = $tmpcategory->getName();
            $notes[$catname] = $tmpcategory->getNotes();
        }

        $newnote = new Note();
        $noteform = $this->createForm(NewNoteFormType::class, $newnote);
        $noteform->handleRequest($request);
        if ($noteform->isSubmitted() && $noteform->isValid()) {
            $newnote->setOwner($user);
            $newnote->setContent('');
            $entityManager->persist($newnote);
            $entityManager->flush();
            // do anything else you need here, like send an email
            return $this->redirectToRoute('notes');
        }
        $newcat = new Category();
        $catform = $this->createForm(NewCatFormType::class, $newcat);
        $catform->handleRequest($request);
        if ($catform->isSubmitted() && $catform->isValid()) {
            $newcat->setOwner($user);
            $entityManager->persist($newcat);
            $entityManager->flush();
            // do anything else you need here, like send an email
            return $this->redirectToRoute('notes');
        }

        return $this->render('notes.html.twig', [
            'notes' => $notes,
            'usercategories' => $usercategories,
            'create_cat' => $catform->createView(),
            'create_note' => $noteform->createView(),
 //           'delete_note' => $deletenotefrom,
 //           'move_note' => $movenotefrom,
        ]);
    }
    // ajax post request to sync notes
    /**
    * @Route("/notes/{id}/sync")
    */
    public function sync(EntityManagerInterface $entityManager, Request $request): Response
    {
        $content = $request->request->get('content') ?? '';
        // return if contains non base64 characters
        if(!preg_match('/^[a-zA-Z0-9+\/=]+$/', $content)){
            return new Response('Invalid input', 400);
        }
        $currentnote = $entityManager->getRepository(Note::class)->find($request->get('id'));
        if($currentnote == NULL){ return new Response('Note not found', 404); }
        $currentnote->setContent("$content");
        $entityManager->flush();
        return $this->json(['content' => $content]);
    }
    /**
    * @Route("/notes/{id}", name="notes{id}")
    */
    public function note(EntityManagerInterface $entityManager,Request $request): Response{
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $currentnote = $entityManager->getRepository(Note::class)->find($request->get('id'));
        $content = $currentnote->getContent();
        // remove all non base64 characters
        $content = preg_replace('/[^A-Za-z0-9\+\/\=]/', '', $content);
        if($currentnote == NULL){
            return $this->redirectToRoute('notes');
        }
        if($currentnote->getOwner() != $user){
            return $this->redirectToRoute('notes');
        }
        $usercategories = $user->getCategories();
        $notes = array();
        $uncatnotes = array();
        foreach($user->getNotes() as $tmpnote){
            if($tmpnote->getCategory() == NULL){
                array_push($uncatnotes, $tmpnote);
            }
        }
        $notes['Unsoriert']= $uncatnotes;
        foreach($usercategories as $tmpcategory){
            $catname = $tmpcategory->getName();
            $notes[$catname] = $tmpcategory->getNotes();
        }

        $newnote = new Note();
        $noteform = $this->createForm(NewNoteFormType::class, $newnote);
        $noteform->handleRequest($request);
        if ($noteform->isSubmitted() && $noteform->isValid()) {
            $newnote->setOwner($user);
            $newnote->setContent('');
            $entityManager->persist($newnote);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $url = $this->generateUrl('notes');
            $url = $url.'/'.$request->get('id');
            return $this->redirect($url);
        }
        $newcat = new Category();
        $catform = $this->createForm(NewCatFormType::class, $newcat);
        $catform->handleRequest($request);
        if ($catform->isSubmitted() && $catform->isValid()) {
            $newcat->setOwner($user);
            $entityManager->persist($newcat);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $url = $this->generateUrl('notes');
            $url = $url.'/'.$request->get('id');
            return $this->redirect($url);
        }
        $movenote = new moveTask();
        $movenoteform = $this->createForm(MoveNoteFormType::class, $movenote);
        $movenoteform->handleRequest($request);
        if ($movenoteform->isSubmitted() && $movenoteform->isValid()) {
            $note = $entityManager->getRepository(Note::class)->find($request->get('id'));
            $note->setCategory($movenote->getcategory());
            $entityManager->flush();
            // do anything else you need here, like send an email
            $url = $this->generateUrl('notes');
            $url = $url.'/'.$request->get('id');
            return $this->redirect($url);
        }   
        $deletenote = new deleteTask();
        $deletenoteform = $this->createForm(DeleteNoteFormType::class, $deletenote);
        $deletenoteform->handleRequest($request);
        if ($deletenoteform->isSubmitted() && $deletenoteform->isValid()) {
            $note = $entityManager->getRepository(Note::class)->find($request->get('id'));
            $entityManager->remove($note);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('notes');
        }

        return $this->render('notes.html.twig', [
            'content' => $content,
            'notes' => $notes,
            'currentID' => $request->get('id'),
            'usercategories' => $usercategories,
            'create_cat' => $catform->createView(),
            'create_note' => $noteform->createView(),
            'move_note' => $movenoteform->createView(),
            'delete_note' => $deletenoteform->createView(),
            //'notes2' => $form->createView()
        ]);
    }
}