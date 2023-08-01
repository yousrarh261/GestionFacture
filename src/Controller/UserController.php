<?php


namespace App\Controller;

use App\Entity\Utilisateurs;
use App\Entity\Responsable;
use App\Form\UserType;
use App\Form\EditProfileType;
use App\Form\UtilisateurType;
use App\Repository\UtilisateursRepository;
use App\Repository\ResponsableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route ("/utilisateur", name="utilisateur_")
 */
class UserController extends AbstractController
{
    private $repository;
    private $em;

    public function __construct(UtilisateursRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/user", name="user")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $user = $this->repository->findBy(array(),array('roles'=>'ASC'));
        return $this->render('client/index.html.twig', [
            "user" => $user,
        ]);
    }


        

    /**
     * @return string
     * @throws \Exception
     */
    private function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }

 
    
   

    /**
     *@Route("/profile", name="profile")
     */
    
     public function profile(): Response
     {
         return $this->render('user/profil.html.twig');
     }

     /**
      * @Route("/name/modifier", name="name_editer")
      */
     public function editProfile(Request $request)
     {
         $user = $this->container->get('security.token_storage')->getToken()->getUser();
         $form = $this->createForm(EditProfileType::class, $user);
         $form->handleRequest($request);
 
         if ($form->isSubmitted() && $form->isValid()) {
          
             $em = $this->getDoctrine()->getManager();
             $em->persist($user);
             $em->flush();
             $this->addFlash('message', 'Profile mis à jour !');
             return $this->redirectToRoute('profile');
         }
 
 
         return $this->render('user/editProfil.html.twig', [
             'form' => $form->createView(),
         ]);
     }
 
 
     /**
      * @Route("/pass/modifier", name="passe_edit")
      */
     public function editPass(Request $request, UserPasswordEncoderInterface $passwordEncoder)
     {
         if ($request->isMethod('POST')) {
             $em = $this->getDoctrine()->getManager();
 
             $user = $this->getUser();
 
             // On vérifie si les 2 mots de passe sont identiques
             if ($request->request->get('pass') == $request->request->get('pass2')) {
                 $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('pass')));
                 $em->flush();
                 $this->addFlash('message', 'Mot de passe mis à jour avec succès');
 
                 return $this->redirectToRoute('profile');
             } else {
                 $this->addFlash('error', 'Les deux mots de passe ne sont pas identiques');
             }
         }
 
         return $this->render('user/EditPasse.html.twig');
     }
}
  
