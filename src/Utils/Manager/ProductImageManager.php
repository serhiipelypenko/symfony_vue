<?php

namespace App\Utils\Manager;

use App\Entity\ProductImage;
use App\Utils\File\ImageResizer;
use App\Utils\Filesystem\FilesystemWorker;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class ProductImageManager extends AbstractBaseManager
{
    public function __construct(
        EntityManagerInterface $entityManager,
        private FilesystemWorker $filesystemWorker,
        private ImageResizer $imageResizer,
        private string $uploadsTempDir)
    {
        parent::__construct($entityManager);
    }


    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(ProductImage::class);
    }

    public function saveImageForProduct(string $productDir, string $tempImageFilename = null)
    {
        if(!$tempImageFilename) {
            return null;
        }
        $this->filesystemWorker->createFolderIfItNotExist($productDir);
        $filenameId = uniqid();
        $imagesSmallParams = [
            'width' => 60,
            'height' => null,
            'newFolder' => $productDir,
            'newFilename' => sprintf('%s_%s.jpg', $filenameId,'small'),
        ];
        $imageSmall = $this->imageResizer->resizeImageAndSave($this->uploadsTempDir, $tempImageFilename,$imagesSmallParams);

        $imagesMiddleParams = [
            'width' => 430,
            'height' => null,
            'newFolder' => $productDir,
            'newFilename' => sprintf('%s_%s.jpg', $filenameId,'middle'),
        ];
        $imageMiddle = $this->imageResizer->resizeImageAndSave($this->uploadsTempDir, $tempImageFilename,$imagesMiddleParams);

        $imagesBigParams = [
            'width' => 800,
            'height' => null,
            'newFolder' => $productDir,
            'newFilename' => sprintf('%s_%s.jpg', $filenameId,'big'),
        ];
        $imageBig = $this->imageResizer->resizeImageAndSave($this->uploadsTempDir, $tempImageFilename,$imagesBigParams);

        $productImage = new ProductImage();
        $productImage->setFilenameSmall($imageSmall);
        $productImage->setFilenameMiddle($imageMiddle);
        $productImage->setFilenameBig($imageBig);

        return $productImage;

    }


    public function removeImageFromProduct(ProductImage $productImage, string $productDir){

        $smallFilePath = $productDir.'/'.$productImage->getFilenameSmall();
        $this->filesystemWorker->remove($smallFilePath);
        $middleFilePath = $productDir.'/'.$productImage->getFilenameMiddle();
        $this->filesystemWorker->remove($middleFilePath);
        $bigFilePath = $productDir.'/'.$productImage->getFilenameBig();
        $this->filesystemWorker->remove($bigFilePath);

        $product = $productImage->getProduct();
        $product->removeProductImage($productImage);

        $this->entityManager->flush();
    }

}
