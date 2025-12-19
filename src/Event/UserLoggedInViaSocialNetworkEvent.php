<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserLoggedInViaSocialNetworkEvent extends Event
{

    public function __construct(private User $user, private string $plainPassword){

    }

    public function getUser(): User{
        return $this->user;
    }
    public function getPlainPassword(): string{
        return $this->plainPassword;
    }

}
