<?php

namespace App\Form\DTO;

use App\Entity\Category;
use Symfony\Component\Validator\Constraints as Assert;

class EditCategoryModel{

    public ?int $id = null;

    #[Assert\NotBlank(message: 'Please enter a title')]
    public string $title;


    public static function makeFromCategory(?Category $category): self
    {
        $model = new self();
        if(!$category){
            return $model;
        }

        $model->id = $category->getId();
        $model->title = $category->getTitle();

        return $model;
    }
}
