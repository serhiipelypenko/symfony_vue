<?php

namespace App\Form\Handler;

use App\Entity\Product;
use App\Form\DTO\EditProductModel;
use App\Utils\File\FileSaver;
use App\Utils\Manager\ProductManager;
use Symfony\Component\Form\FormInterface;

readonly class ProductFormHandler{

    public function __construct(
        private ProductManager $productManager,
        private FileSaver $fileSaver)
    {

    }

    public function processEditForm(EditProductModel $editProductModel, FormInterface $form){

        $product = new Product();

        if($editProductModel->id){
            $product = $this->productManager->find($editProductModel->id);
        }

        $product->setTitle($editProductModel->title);
        $product->setPrice($editProductModel->price);
        $product->setQuantity($editProductModel->quantity);
        $product->setDescription($editProductModel->description);
        $product->setIsPublish($editProductModel->isPublish);
        $product->setIsDeleted($editProductModel->isDeleted);

        $this->productManager->save($product);
        $newImageFile = $form->get('newImage')->getData();

        $tempImageFilename = $newImageFile
            ? $this->fileSaver->saveUploadFileIntoTemp($newImageFile)
            : null;

        $this->productManager->updateProductImages($product, $tempImageFilename);

        $this->productManager->save($product);
        return $product;
    }

}
