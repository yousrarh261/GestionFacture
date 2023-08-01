<?php

namespace App\services;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class Mailer
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    public function sendEmail($email, $token)
    {
        $email = (new TemplatedEmail())
            ->from('noreplay@solway.com')
            ->to('yrhalmani@gmail.com')
            ->subject('Valider  le compte utilisateur')

            // path of the Twig template to render
            ->htmlTemplate('Email/activation.html.twig')

            // pass variables (name => value) to the template
            ->context([
                'token' => $token,
            ]);

        $this->mailer->send($email);
    }
    public function send($email, $token){
        $email = (new TemplatedEmail())

->from('noreplay@solway-cs.com')
->to(new Address($email))
->subject('Validation de votre compte chez SOLWAY Consulting & Services ')
->htmlTemplate('Email/validation.html.twig')
->context(['token' => $token,
    ]);
$this->mailer->send($email);
    }
    

}