<?php

namespace App\EventSubscriber;

use App\Event\OrderCreatedFromCartEvent;
use App\Event\UserLoggedInViaSocialNetworkEvent;
use App\Utils\Mailer\Sender\OrderCreatedFromCartEmailSender;
use App\Utils\Mailer\Sender\UserLoggedInViaSocialNetworkEmailSender;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserLoggedInViaSocialNetworkNotificationSubscriber  implements EventSubscriberInterface
{

    public function __construct(private readonly UserLoggedInViaSocialNetworkEmailSender $emailSender){

    }
    public function onUserLoggedInViaSocialNetworkEvent(UserLoggedInViaSocialNetworkEvent $event): void
    {
        $user = $event->getUser();
        $plainPassword = $event->getPlainPassword();
        $this->emailSender->sendEmailToClient($user, $plainPassword);
    }

    public static function getSubscribedEvents()
    {
        return [
            UserLoggedInViaSocialNetworkEvent::class => 'onUserLoggedInViaSocialNetworkEvent',
        ];
    }
}
