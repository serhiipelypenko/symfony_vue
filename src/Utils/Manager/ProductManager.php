<?php

namespace App\Utils\Manager;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

 class ProductManager
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ProductImageManager $productImageManager,
        private string $productImagesDir)
    {
    }

    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Product::class);
    }

    public function save(Product $product){
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    public function remove(){

    }

    public function getProductImagesDir(Product $product): string
    {
        return sprintf('%s/%s',$this->productImagesDir,$product->getId());
    }

    public function updateProductImages(Product $product, string $tempImageFilename = null): Product
    {
        if(!$tempImageFilename){
            return $product;
        }
        $productDir = $this->getProductImagesDir($product);

        $productImage = $this->productImageManager->saveImageForProduct($productDir,$tempImageFilename);
        $productImage->setProduct($product);
        $product->addProductImage($productImage);
        return $product;
    }

}
