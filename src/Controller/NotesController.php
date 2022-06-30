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
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Note;
use App\Entity\Invitaion;
use App\Form\NewCatFormType;


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
    public function index(): Response
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
        // $notes = [
        //     'Categorie 1' => [
        //         'Note 1',
        //         'Note 2',
        //         'Note 3',
        //     ],
        //     'Categorie 2' => [
        //         'Note 4',
        //         'Note 5',
        //         'Note 6',
        //     ],
        //     'Categorie 3' => [
        //         'Note 7',
        //         'Note 8',
        //         'Note 9',
        //         'Note 10',
        //         'Note 11',
        //         'Note 12',
        //         'Note 13',
        //     ],
        // ];
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
    /**
    * @Route("/notes/{id}", name="notes{id}")
    */
    public function note(EntityManagerInterface $entityManager): Response{
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $allnotes = $user->getNotes();
        $usercategories = $user->getCategories();
        $uncategory = array();
        $notes = array();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('notes1');	
        }


        $test = "sd";
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
        return $this->render('notes.html.twig', [
            'notes' => $notes,
            'registrationForm' => $form->createView()       ]);
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