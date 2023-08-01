<?php

namespace App\Controller\Manager;

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
 * @Route ("/manager", name="manager_")
 */
class UtilisateurController extends AbstractController
{
    private $repository;
    private $em;

    public function __construct(UtilisateursRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
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

        return $this->redirectToRoute("manager_profile");
    }
    

    /**
     *@Route("/profile", name="profile")
     */
    
     public function profile(): Response
     {
         return $this->render('Manager/client/profil.html.twig');
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
          
             $em = $this->getDoctrine()->getManager();
             $em->persist($user);
             $em->flush();
             $this->addFlash('message', 'Profile mis à jour !');
             return $this->redirectToRoute('manager_profile');
         }
 
 
         return $this->render('Manager/client/editProfil.html.twig', [
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
 
                 return $this->redirectToRoute('manager_profile');
             } else {
                 $this->addFlash('error', 'Les deux mots de passe ne sont pas identiques');
             }
         }
 
         return $this->render('Manager/client/EditPasse.html.twig');
     }
}
  
