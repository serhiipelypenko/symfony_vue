<?php

namespace App\Utils\Manager;

use App\Entity\Category;
use Doctrine\Persistence\ObjectRepository;

 class CategoryManager extends AbstractBaseManager
{
    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Category::class);
    }

    public function remove(object $category)
    {
        $category->setIsDeleted(true);
        foreach ($category->getProducts() as $product) {
            $product->setIsDeleted(true);
        }
        $this->save($category);
    }

 }
