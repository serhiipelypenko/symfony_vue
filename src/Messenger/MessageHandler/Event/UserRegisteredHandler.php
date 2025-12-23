<?php

namespace App\Messenger\MessageHandler\Event;
use App\Entity\User;
use App\Messenger\Message\Event\UserRegisteredEvent;
use App\Security\Verifier\EmailVerifier;
use App\Utils\Mailer\Sender\UserRegisteredEmailSender;
use App\Utils\Manager\UserManager;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UserRegisteredHandler
{
    public function __construct(
        private EmailVerifier $emailVerifier,
        private UserManager $userManager,
        private  UserRegisteredEmailSender $emailSender){}
    public function __invoke(UserRegisteredEvent $event): void
    {
       $userId = $event->getUserId();

       /** @var User $user */
       $user = $this->userManager->find($userId);

       if(!$user){
           return;
       }

       $emailSignature = $this->emailVerifier->generateEmailSignature('main_verify_email',$user);
       $this->emailSender->sendEmailToClient($user, $emailSignature);
    }
}
