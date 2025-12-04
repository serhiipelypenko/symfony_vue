<?php

namespace App\Utils\Mailer;

use App\Utils\Mailer\DTO\MailerOptions;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class MailerSender
{
    public function __construct(private readonly MailerInterface $mailer, private readonly LoggerInterface $logger){}

    public function sendTemplateEmail(MailerOptions $mailerOptions): TemplatedEmail
    {
        $email = (new TemplatedEmail())
            ->to($mailerOptions->getRecipient())
            ->subject($mailerOptions->getSubject())
            ->htmlTemplate($mailerOptions->getHtmlTemplate())
            ->context($mailerOptions->getContext());

        if($mailerOptions->getCc()){
            $email->cc($mailerOptions->getCc());
        }

        try{
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $exception){
            $this->logger->critical($mailerOptions->getSubject(), [
                'errorText' => $exception->getTraceAsString(),
            ]);

            $systemMailerOptions = new MailerOptions();
            $systemMailerOptions->setText($exception->getTraceAsString());

            $this->sendSystemEmail($systemMailerOptions);

        }

        return $email;
    }

    private function sendSystemEmail(MailerOptions $mailerOptions): void
    {
        $mailerOptions->setSubject('[Exception] An error has occurred!');
        $mailerOptions->setRecipient('admin@ranked-choice.com');
        $email = (new TemplatedEmail())
            ->to($mailerOptions->getRecipient())
            ->subject($mailerOptions->getSubject())
            ->text($mailerOptions->getText());


        try{
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $exception){
            $this->logger->critical($mailerOptions->getSubject(), [
                'errorText' => $exception->getTraceAsString(),
            ]);
        }
    }

}
