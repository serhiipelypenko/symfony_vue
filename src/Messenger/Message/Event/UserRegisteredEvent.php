<?php

namespace App\Messenger\Message\Event;


class UserRegisteredEvent
{

    public function __construct(private string $userId){}

    public function getUserId(){
        return $this->userId;
    }
}
