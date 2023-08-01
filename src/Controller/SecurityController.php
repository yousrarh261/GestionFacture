<?php

namespace App\Controller;

use App\Entity\Utilisateurs;
use App\Form\ResetPasswordRequestFormType; 
use App\Form\ResetPassType;
use App\Form\EditProfileType; 

use App\Repository\UtilisateursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{

    /**
     * @Route("/", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('profile');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
       //---------------------------------------profil utilisateurs-----------------------------


    /**
     * @Route("/profile", name="profile")
     */

     public function profile(): Response
     {
         return $this->render('User/profil.html.twig');
     }
 
 
     /**
      * @Route("/profile/name/modifier", name="user_name_edit")
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
 
         return $this->render('client/editProfil.html.twig', [
             'form' => $form->createView(),
         ]);
     }
 
 
  /**
      * @Route("/profile/picture/add", name="user_add_picture")
      */
 
 
 
 
     /**
      * @Route("/profile/pass/modifier", name="user_passe_edit")
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
 
         return $this->render('client/Editpasse.html.twig');
     }

}