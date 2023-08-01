<?php

namespace App\Security;

use App\Entity\Utilisateurs as AppUser;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;



class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }
        
    }

    public function checkPostAuth(UserInterface $user, ): void
    {
        if (!$user instanceof AppUser) {
            return;
        }
        if (!$user->getIsVerified()) {
            // the message passed to this exception is meant to be displayed to the user
            throw new CustomUserMessageAccountStatusException('Votre compte n\'est pas encore approuver par l\'admin. Merci d\'attendre la confirmation de votre inscription .' );
        }
  
        if (!$user->getIsValidByAdmin() && $role = "ROLE_USER") {
            // the message passed to this exception is meant to be displayed to the user
            throw new CustomUserMessageAccountStatusException('Votre compte n\'est pas encore activé par l\' admin.');
        }
        
}
}

    
