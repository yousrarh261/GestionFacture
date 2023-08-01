<?php

namespace App\Controller;

use App\Entity\Utilisateurs;
use App\Form\RegistrationFormType;
use App\services\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, Mailer $mailer): Response
    {
        $user = new Utilisateurs();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {// encode the plain password
            $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                $form->get('plainPassword')->getData()
                )
            );
            $user->setActivationToken($this->generateToken());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash("notice", "votre compte a été crée avec succès ,consultez vos mails pour le valider!");
            $mailer->sendEmail($user->getEmail(), $user->getActivationToken());
            $mailer->send($user->getEmail(), $user->getActivationToken());

// do anything else you need here, like send an email

            return $this->redirectToRoute('app_register');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/confirmer-mon-compte/{token}", name="confirm_account")
     * @param string $token
     */
    public function confirmAccount(string $token, Mailer $mailer): Response
    {
        $user = $this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(["activation_token" => $token]);
        if ($user) {
            $user->setIsVerified(true);
            $user->setActivationToken(null);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            
            // Check if the account is validated by admin
            if ($user->getIsValidByAdmin()) {
                $user->setEnabled(true);
                $em->persist($user);
                $em->flush();
                $this->addFlash("notice", "Votre compte est maintenant activé !");
                return $this->redirectToRoute("app_login");
            } else {
                $this->addFlash("notice", "Votre compte est validé par l'administrateur !");
                return $this->redirectToRoute("app_register");
            }
        } else {
            $this->addFlash("error", "Le lien de confirmation est invalide !");
            return $this->redirectToRoute('app_register');
        }
    }
    
    
    
    /**
     * @Route("/valider-mon-compte/{token}", name="valid_account")
     * @param string $token
     */
    public function validAccount(string $token, Mailer $mailer): Response
{
    $user = $this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(["activation_token" => $token]);
    if ($user) {
        $user->setIsValidByAdmin(true);
        $user->setIsVerified(false);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        $this->addFlash("notice","Votre email a été bien validé. Merci d'attendre l'approuvation de l'admin.");
        return $this->redirectToRoute("app_register");
    } else {
        $this->addFlash("error", "Le lien de confirmation est invalide !");
        return $this->redirectToRoute('app_register');
    }
}
/**
    * @return string
     * @throws \Exception
     */
    private function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}