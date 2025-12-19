<?php

namespace App\Utils\Mailer\Sender;

use App\Entity\User;
use App\Utils\Mailer\DTO\MailerOptions;
use App\Utils\Mailer\MailerSender;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserLoggedInViaSocialNetworkEmailSender
{
    public function __construct(private MailerSender $mailerSender, private UrlGeneratorInterface $urlGenerator){

    }

    public function sendEmailToClient(User $user, string $plainPassword){
        $mailerOptions = (new MailerOptions())
            ->setRecipient($user->getEmail())
            ->setSubject('Ranked Choice Shop - Ypur new password')
            ->setHtmlTemplate('main/email/client/user_logged_in_via_social_network.html.twig')
            ->setContext([
                'user' => $user,
                'plainPassword' => $plainPassword,
                'profileUrl' => $this->urlGenerator->generate('main_profile_index', [], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);
        $this->mailerSender->sendTemplateEmail($mailerOptions);
    }

}
