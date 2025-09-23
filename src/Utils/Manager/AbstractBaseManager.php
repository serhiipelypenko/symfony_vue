<?php

namespace App\Utils\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

abstract class AbstractBaseManager
{
    public function __construct(protected EntityManagerInterface $entityManager)
    {

    }

    abstract public function getRepository(): ObjectRepository;

    public function find(int $id): ?object
    {
        return $this->getRepository()->find($id);
    }

    public function save(object $entity){
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function remove(object $entity){
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
}
