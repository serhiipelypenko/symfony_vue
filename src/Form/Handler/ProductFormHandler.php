<?php

namespace App\Form\Handler;

use App\Entity\Product;
use App\Utils\File\FileSaver;
use App\Utils\Manager\ProductManager;
use Symfony\Component\Form\FormInterface;

readonly class ProductFormHandler{

    public function __construct(
        private ProductManager $productManager,
        private FileSaver $fileSaver)
    {

    }

    public function processEditForm(Product $product, FormInterface $form){

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
