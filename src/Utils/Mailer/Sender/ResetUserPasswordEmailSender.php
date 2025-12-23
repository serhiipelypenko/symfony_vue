<?php

namespace App\Utils\Mailer\Sender;

use App\Entity\User;
use App\Utils\Mailer\DTO\MailerOptions;
use App\Utils\Mailer\MailerSender;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordToken;
use SymfonyCasts\Bundle\VerifyEmail\Model\VerifyEmailSignatureComponents;

class ResetUserPasswordEmailSender
{
    public function __construct(
        private MailerSender $mailerSender,
        private UrlGeneratorInterface $urlGenerator){}

    public function sendEmailToClient(User $user, ResetPasswordToken $resetPasswordToken){

        $emailContext = [];
        $emailContext['resetToken'] = $resetPasswordToken;
        $emailContext['user'] = $user;
        $emailContext['profileUrl'] = $this->urlGenerator->generate('main_profile_index', ['_locale' => 'en'], UrlGeneratorInterface::ABSOLUTE_URL);

        $mailerOptions = (new MailerOptions())
            ->setRecipient($user->getEmail())
            ->setSubject('Ranked Choice Shop - Your password reset request!')
            ->setHtmlTemplate('main/email/security/reset_password.html.twig')
            ->setContext($emailContext);
        $this->mailerSender->sendTemplateEmail($mailerOptions);

    }
}
