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
use Symfony\Component\Validator\Constraints as Assert;
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
use App\Form\InvitationFormType;
use App\Entity\Invitation;
use App\Entity\invTask;



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
        $notes['Unsortiert']= $uncatnotes;
        foreach($usercategories as $tmpcategory){
            $catname = $tmpcategory->getName();
            $notes[$catname] = $tmpcategory->getNotes();
        }
        $invitearray= array();
        $allinviations = array();
        array_splice($allinviations, 0, count($user->getInvitaions()->getValues()), $user->getInvitaions()->getValues());
        foreach($allinviations as $tmpinv){
        if($tmpinv->getNote() != NULL){
                array_push($invitearray, $tmpinv->getNote());
            }
        }
        $note['Invitations'] = $invitearray;

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
        $invnotes = array();
        $allinviations = $user->getInvitedto();
        //print_r($allinviations->getValues());
        foreach($allinviations->getValues() as $tmpinv){
                array_push($invnotes, $tmpinv->getNote());
        }
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
        $notes['Unsortiert']= $uncatnotes;
        foreach($usercategories as $tmpcategory){
            $catname = $tmpcategory->getName();
            $notes[$catname] = $tmpcategory->getNotes();
        }
        $invitearray= array();
        $allinviations = $user->getInvitedto();
        foreach($allinviations->getValues() as $tmpinv){
        if($tmpinv->getNote() != NULL){
                array_push($invitearray, $tmpinv->getNote());
            }
        }
        $note['Invitations'] = $invitearray;

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
            if($movenote->getcategory()->getName() == 'Uncategorized'){
                $id = $request->get('id');
                //Create a Database Query to set the category to NULL
                $query = "UPDATE App\Entity\Note u SET u.category = NULL WHERE u.id = $id";
                $entityManager->createQuery($query)->execute();
                //$note->setCategory(NULL);
                //$entityManager->flush();
            }else{    
            $note->setCategory($movenote->getcategory());
            $entityManager->persist($note);
            $entityManager->flush();
            }

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
        //Create Invitaion Form 

        $newInvitation = new Invitaion();
        $newInvTask = new invTask();
        //$invitees = ($entityManager->getRepository(Invitaion::class)->findOneBy(['owner_id' => $user->getId(), 'note_id' => $request->get('id')]))->getInvitee();
        $repository = $entityManager->getRepository(Invitaion::class);
        if($repository->findOneBy(['owner' => $user->getId(), 'note' => $request->get('id')]) != NULL){

        $invitees = ($repository->findOneBy(['owner' => $user->getId(), 'note' => $request->get('id')]))->getInvitee();
        }
        else{
            $invitees = array();
        }
        $invitationform = $this->createForm(InvitationFormType::class, $newInvTask, ['invitees' => $invitees]);
        $invitationform->handleRequest($request);
        if ($invitationform->isSubmitted() && $invitationform->isValid()) {
            if($newInvTask->getAdd() != NULL){
                $Invitee = $entityManager->getRepository(User::class)->findOneBy(['email' => $newInvTask->getAdd()]);
                if($Invitee == NULL){
                    return $this->redirectToRoute('notes');
                }
                $newInvitation->addInvitee($Invitee);
                $newInvitation->setOwner($user);
                $newInvitation->setNote($currentnote);
                $newInvitation->setAccepted(TRUE);



                $entityManager->persist($newInvitation);
                $entityManager->flush();
                $url = $this->generateUrl('notes');
                $url = $url.'/'.$request->get('id');
                return $this->redirect($url);
            }
            $invitee = $invitationform->getData()->getRemove();
            if($newInvTask->getRemove() != NULL){
                if($invitee == NULL){
                    return $this->redirectToRoute('notes');
                }
                $invitaion = $repository->findOneBy(['owner' => $user->getId(), 'note' => $request->get('id')]);
                $invitaion->removeInvitee($invitee);
                $entityManager->flush();
                $url = $this->generateUrl('notes');
                $url = $url.'/'.$request->get('id');
                return $this->redirect($url);
            }
            // do anything else you need here, like send an email
            $url = $this->generateUrl('notes');
            $url = $url.'/'.$request->get('id');
            return $this->redirect($url);
        }


        return $this->render('notes.html.twig', [
            'content' => $content,
            'notes' => $notes,
            'currentNote' => $currentnote,
            'usercategories' => $usercategories,
            'invitationform' => $invitationform->createView(),
            'create_cat' => $catform->createView(),
            'create_note' => $noteform->createView(),
            'move_note' => $movenoteform->createView(),
            'delete_note' => $deletenoteform->createView(),
            //'notes2' => $form->createView()
        ]);
    }
}