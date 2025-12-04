<?php

namespace App\Utils\Mailer\Sender;

use App\Entity\Order;
use App\Utils\Mailer\DTO\MailerOptions;

class OrderCreatedFromCartEmailSender
{
    public function sendEmailToClient(Order $order){
        $mailerOptions = (new MailerOptions())
            ->setRecipient($order->getOwner()->getEmail())
            ->setCc('manager@ranked-choice.com')
            ->setSubject('Ranked Choice Shop - Thank you for your purchase!')
            ->setHtmlTemplate('main/email/client/created_order_from_cart.html.twig')
            ->setContext([
                'order' => $order,
            ]);
    }

    public function sendEmailToManager(Order $order){

    }

}
