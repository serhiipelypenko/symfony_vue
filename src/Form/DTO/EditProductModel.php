<?php

namespace App\Form\DTO;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class EditProductModel{

    public ?int $id = null;

    #[Assert\NotBlank(message: 'Please enter a title')]
    public string $title;

    #[Assert\NotBlank(message: 'Please enter a price')]
    #[Assert\GreaterThanOrEqual(value: 5)]
    public float $price;

    #[Assert\File(maxSize: "5024k", mimeTypes: "image/png, image/jpeg", mimeTypesMessage: 'Please upload a valid image file.')]
    public UploadedFile|null $newImage;
    #[Assert\NotBlank(message: 'Please enter a quantity')]
    public int $quantity;
    public string $description;

    #[Assert\NotBlank(message: 'Please choose a category')]
    public Category $category;

    public bool $isPublish;

    public bool $isDeleted;

    public static function makeFromProduct(?Product $product): self
    {
        $model = new self();
        if(!$product){
            return $model;
        }

        $model->id = $product->getId();
        $model->title = $product->getTitle();
        $model->price = $product->getPrice();
        $model->quantity = $product->getQuantity();
        $model->description = $product->getDescription();
        $model->isPublish = $product->isPublish();
        $model->isDeleted = $product->isDeleted();

        return $model;
    }
}
