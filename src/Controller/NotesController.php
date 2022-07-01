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
use App\Entity\Invitaion;
use App\Form\NewCatFormType;
use App\Form\MoveNoteFormType;


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
        $uncategory = array();
        $notes = array();
        foreach($allnotes as $tmpnote){
            if($tmpnote->getCategory() == NULL){
                array_push($uncategory, $tmpnote->getTitle());
            }
        }
        $notes['Unsoriert']= $uncategory;
        foreach($usercategories as $tmpcategory){
            $tmpname = $tmpcategory->getName();
            ${"$tmpname"} = array();
            foreach($tmpcategory->getNotes() as $tmpnote){
                array_push(${"$tmpname"}, $tmpnote->getTitle());
            }
            array_push($notes, ${"$tmpname"});
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
    * @Route("/notes/sync")
    */
    public function sync(Request $request): Response
    {
        // return the request as json
        return $this->json($request->request->all());
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
            return $this->redirectToRoute('notes/'.$request->get('id'));
        }
        $newcat = new Category();
        $catform = $this->createForm(NewCatFormType::class, $newcat);
        $catform->handleRequest($request);
        if ($catform->isSubmitted() && $catform->isValid()) {
            $newcat->setOwner($user);
            $entityManager->persist($newcat);
            $entityManager->flush();
            // do anything else you need here, like send an email
            return $this->redirectToRoute('notes/'.$request->get('id'));
        }
        $movenoteform = $this->createForm(MoveNoteFormType::class);
        $movenoteform->handleRequest($request);
        if ($movenoteform->isSubmitted() && $movenoteform->isValid()) {
            $note = $entityManager->getRepository(Note::class)->find($request->get('id'));
            $note->setCategory($movenoteform['category']->getData());
            // do anything else you need here, like send an email
            $url = $this->generateUrl('notes');
            $url = $url.$request->get('id');
            return $this->redirectToRoute($url);
        }

        return $this->render('notes.html.twig', [
            'content' => $content,
            'notes' => $notes,
            'usercategories' => $usercategories,
            'create_cat' => $catform->createView(),
            'create_note' => $noteform->createView(),
            'move_note' => $movenoteform->createView(),
            //'notes2' => $form->createView()
        ]);
    }
    /**
    * @Route("/notes/new", name="newCat")
    */
    public function newCat(Request $request): Response
    {
        $cat = new Category();
        $user  = $this->getUser();
        $form = $this->createForm(NewCatFormType::class, $cat);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cat = $form->getData();
            $cat->setOwner($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($cat);
            $em->flush();
            return $this->redirectToRoute('notes');
        }
        $response = new Response();
        $response->setContent('<html><body><h1>Ich Hasse Web Entwicklung</h1></body></html>');
        $response->headers->set('Content-Type', 'text/html');
        $response->setStatusCode(Response::HTTP_OK);
        return $response
       ;
    }
}