<?php

namespace App\Messenger\Message\Command;

class ResetUserPassword
{
    public function __construct(private string $email){}

    public function getEmail(){
        return $this->email;
    }

}
