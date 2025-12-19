<?php

namespace App\Utils\Manager;

use App\Entity\User;
use App\Exception\Security\EmptyUserPlainPasswordException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager extends AbstractBaseManager
{
    public function __construct(protected EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher){
        parent::__construct($entityManager);
    }
    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(User::class);
    }

    public function encodePassword(User $user, string $plainPassword): void
    {
        //$user->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));
        $newPassword = trim($plainPassword);
        if(!$newPassword){
            throw new EmptyUserPlainPasswordException('Password cannot be empty');
        }

        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $newPassword)
        );
    }

    public function remove(object $user)
    {
        $user->setIsDeleted(true);
        $this->save($user);
    }

 }
