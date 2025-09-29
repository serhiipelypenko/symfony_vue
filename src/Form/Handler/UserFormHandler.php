<?php

namespace App\Form\Handler;

use App\Entity\User;
use App\Utils\Manager\UserManager;
use Symfony\Component\Form\FormInterface as Form;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class UserFormHandler{

    public function __construct(
        private UserManager $userManager,
        private UserPasswordHasherInterface $passwordHasher)
    {

    }

    public function processEditForm(Form $form){
        $plainPassword = $form->get('plainPassword')->getData();
        $newEmail = $form->get('newEmail')->getData();

        $user = $form->getData();

        if(!$user->getId()){
            $user->setEmail($newEmail);
        }


        if($plainPassword){
            $encodedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($encodedPassword);
        }
        $this->userManager->save($user);
        return $user;
    }

}
