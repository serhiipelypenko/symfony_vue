<?php

namespace App\Messenger\MessageHandler\Command;

use App\Messenger\Message\Command\ResetUserPassword;
use App\Utils\Mailer\MailerSender;
use App\Utils\Mailer\Sender\ResetUserPasswordEmailSender;
use App\Utils\Manager\UserManager;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

#[AsMessageHandler]
class ResetUserPasswordHandler
{
    public function  __construct(private UserManager $userManager,
        private ResetPasswordHelperInterface $resetPasswordHelper,
        private ResetUserPasswordEmailSender $emailSender)
    {

    }

    public function __invoke(ResetUserPassword $resetUserPassword){
        $email = $resetUserPassword->getEmail();
        $resetToken = null;

        $user = $this->userManager->getRepository()->findOneBy(['email'=>$email]);

        if(!$user){
            return '';
        }

        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
            $this->emailSender->sendEmailToClient($user, $resetToken);
        } catch (ResetPasswordExceptionInterface $exception){
            //
        }
    }
}
