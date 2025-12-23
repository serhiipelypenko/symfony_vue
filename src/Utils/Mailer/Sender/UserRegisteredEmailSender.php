<?php

namespace App\Utils\Mailer\Sender;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\User;
use App\Utils\Mailer\DTO\MailerOptions;
use App\Utils\Mailer\MailerSender;
use SymfonyCasts\Bundle\VerifyEmail\Model\VerifyEmailSignatureComponents;

class UserRegisteredEmailSender
{
    public function __construct(
        private MailerSender $mailerSender,
        private UrlGeneratorInterface $urlGenerator){}

    public function sendEmailToClient(User $user, VerifyEmailSignatureComponents $signatureComponents){

        $emailContext = [];
        $emailContext['signedUrl'] = $signatureComponents->getSignedUrl();
        $emailContext['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $emailContext['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();
        $emailContext['user'] = $user;
        $emailContext['profileUrl'] = $this->urlGenerator->generate('main_profile_index', ['_locale' => 'en'], UrlGeneratorInterface::ABSOLUTE_URL);

        $mailerOptions = (new MailerOptions())
            ->setRecipient($user->getEmail())
            ->setSubject('Ranked Choice Shop - Please confirm your email!')
            ->setHtmlTemplate('main/email/security/confirmation_email.html.twig')
            ->setContext($emailContext);
        $this->mailerSender->sendTemplateEmail($mailerOptions);

    }
}
