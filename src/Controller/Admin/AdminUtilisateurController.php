<?php


namespace App\Controller\Admin;

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
 * @Route ("/admin", name="admin_")
 */
class AdminUtilisateurController extends AbstractController
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
        
        return $this->render('admin/user/index.html.twig', [
            "user" => $user,
        ]);
    }

/**
     * @Route("/user/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new Utilisateurs();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $user->setActivationToken($this->generateToken());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash("notice", "le compte a été crée succes!");
            //return $this->redirectToRoute('admin_user_new');
            
        }

        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
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
  * @route ("/utilisateur/{id}", name="edit_user")
  */
    public function editRoles(Request $request , 
    Utilisateurs $user,ManagerRegistry $doctrine){
        $form = $this->createForm(UtilisateurType::class, $user, array(
            'is_edit' => true,
        ));
        
      
       
        $form->handleRequest($request);
      
        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->persist($user);
            $doctrine->getManager()->flush();
    
            return $this->redirectToRoute('admin_user');
        
        }
    

        return $this->render('admin/user/EditRoles.html.twig', [
            'ticket' => $user,
            'form' => $form->createView(),
        ]);
    
}
    
    /**
     * @Route("/user/{id}", name="delete_user")
     * @param Utilisateurs $utilisateurs
     * @return RedirectResponse
     */
    public function delete(Utilisateurs $utilisateurs): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($utilisateurs);
        $em->flush();

        return $this->redirectToRoute("admin_user");
    }
    

    /**
     *@Route("/profile", name="profile")
     */
    
     public function profile(): Response
     {
         return $this->render('admin/user/profil.html.twig');
     }
      
    
     /**
      * @Route("/name/modifier", name="name_edit")
      */
     public function editProfile(Request $request)
     {
         $user = $this->container->get('security.token_storage')->getToken()->getUser();
         $form = $this->createForm(EditProfileType::class, $user);
         $form->handleRequest($request);
 
         if ($form->isSubmitted() && $form->isValid()) {
          //   $file = $user->getPhoto(); 
            // $fileName = md5(uniqid()).'.'.$file->guessExtension(); 
             //$file->move($this->getParameter('upload_directory'), $fileName); 
             //$user->setPhoto($fileName); 
            // return new Response("User photo is successfully uploaded."); 
          
             $em = $this->getDoctrine()->getManager();
             $em->persist($user);
             $em->flush();
             $this->addFlash('message', 'Profile mis à jour !');
             return $this->redirectToRoute('admin_profile');
         }
 
 
         return $this->render('admin/user/editProfile.html.twig', [
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
 
                 return $this->redirectToRoute('admin_profile');
             } else {
                 $this->addFlash('error', 'Les deux mots de passe ne sont pas identiques');
             }
         }
 
         return $this->render('admin/user/EditPasse.html.twig');
     }
}
  
