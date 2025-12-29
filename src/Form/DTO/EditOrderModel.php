<?php

namespace App\Form\DTO;

use App\Entity\Category;
use App\Entity\Order;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class EditOrderModel{

    public int $id;
    public User $owner;
    public int $status;
    public string $totalPrice;
    public \DateTime $createdAt;


    public static function makeFromOrder(?Order $order): self
    {
        $model = new self();
        if(!$order){
            return $model;
        }

        $model->id = $order->getId();
        $model->owner = $order->getOwner();
        $model->status = $order->getStatus();
        $model->totalPrice = $order->getTotalPrice();
        $model->createdAt = $order->getCreatedAt();

        return $model;
    }
}
