<?php

namespace App\Utils\Manager;

use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\StaticStorage\OrderStaticStorage;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

 class OrderManager extends AbstractBaseManager
{

    public function __construct(protected EntityManagerInterface $entityManager, private CartManager $cartManager){
        parent::__construct($entityManager);
    }

    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Order::class);
    }

    public function createOrderFromCartBySessionId(string $sessionId, User $user){
        $cart = $this->cartManager->getRepository()->findOneBy(['sessionId' => $sessionId]);
        if($cart){
           $this->createOrderFromCart($cart,$user);
        }
    }

    public function createOrderFromCart(Cart $cart, User $user): Order
    {
        $order = new Order();
        $order->setOwner($user);
        $order->setStatus(OrderStaticStorage::ORDER_STATUS_CREATED);
        $orderTotalPrice = 0;


        foreach ($cart->getCartProducts()->getValues() as $cartProduct){
            $orderProduct = new OrderProduct();
            $orderProduct->setAppOrder($order);
            $orderProduct->setQuantity($cartProduct->getQuantity());
            $orderProduct->setPricePerOne($cartProduct->getProduct()->getPrice());
            $orderProduct->setProduct($cartProduct->getProduct());

            $orderTotalPrice += $orderProduct->getPricePerOne() * $orderProduct->getQuantity();

            $order->addOrderProduct($orderProduct);
            $this->entityManager->persist($orderProduct);

        }

        $order->setTotalPrice($orderTotalPrice);
        $order->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        $this->cartManager->remove($cart);
        dd($order);

    }

     public function save(object $entity){
        $entity->setUpdatedAt(new \DateTimeImmutable());
         $this->entityManager->persist($entity);
         $this->entityManager->flush();
     }

    public function remove(object $order)
    {
        $order->setIsDeleted(true);
        $this->save($order);
    }

 }
