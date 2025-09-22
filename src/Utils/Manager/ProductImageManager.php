<?php

namespace App\Utils\Manager;

use App\Entity\ProductImage;
use App\Utils\File\ImageResizer;
use App\Utils\Filesystem\FilesystemWorker;
use Doctrine\ORM\EntityManagerInterface;

readonly class ProductImageManager
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private FilesystemWorker $filesystemWorker,
        private ImageResizer $imageResizer,
        private string $uploadsTempDir)
    {
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


}
