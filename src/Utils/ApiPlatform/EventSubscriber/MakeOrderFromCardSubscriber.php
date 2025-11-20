<?php

namespace App\Utils\ApiPlatform\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Order;

use App\Entity\StaticStorage\OrderStaticStorage;
use App\Utils\Manager\OrderManager;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class MakeOrderFromCardSubscriber implements EventSubscriberInterface {

    public function __construct(private Security $security, private OrderManager $orderManager)
    {
    }

    public function makeOrder(ViewEvent $event)
    {
        $order = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if(!$order instanceof Order || Request::METHOD_POST !== $method) {
            return;
        }
        $user = $this->security->getUser();
        if(!$user) {
            return;
        }
        $order->setOwner($user);
        $contentJson = $event->getRequest()->getContent();
        if(!$contentJson) {
            return;
        }
        $content = json_decode($contentJson, true);
        if(!array_key_exists('cartId', $content)) {
            return;
        }
        $cartId = (int) $content['cartId'];
        $this->orderManager->addOrderProductsFromCart($order, $cartId);
        $this->orderManager->recalculateOrderTotalPrice($order);
        $order->setStatus(OrderStaticStorage::ORDER_STATUS_CREATED);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => [
                [
                    'makeOrder', EventPriorities::PRE_WRITE
                ]
            ]
        ];
    }
}
