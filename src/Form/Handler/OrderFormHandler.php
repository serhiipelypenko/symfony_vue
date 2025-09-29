<?php

namespace App\Form\Handler;

use App\Entity\Order;

use App\Utils\Manager\OrderManager;

readonly class OrderFormHandler{

    public function __construct(
        private OrderManager $orderManager)
    {

    }

    public function processEditForm(Order $order){
        $this->orderManager->save($order);
        return $order;
    }

}
