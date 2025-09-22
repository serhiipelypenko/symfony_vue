<?php

namespace App\Utils\File;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;

class ImageResizer
{

    private Imagine $imagine;

    public function __construct(
       ){
        $this->imagine = new Imagine();
    }


    public function resizeImageAndSave(string $originalFileFolder, string $originalFilename, array $targetParams): string
    {
        $originalFilePath = $originalFileFolder . DIRECTORY_SEPARATOR . $originalFilename;
        list($imageWidth, $imageHeight) = getimagesize($originalFilePath);

        $ratio = $imageWidth / $imageHeight;
        $targetWidth = $targetParams['width'];
        $targetHeight = $targetParams['height'];

        if($targetHeight){
            if($targetWidth / $targetHeight > $ratio){
                $targetWidth = $targetHeight / $ratio;
            }else{
                $targetHeight = $targetWidth / $ratio;
            }
        }else{
            $targetHeight = $targetWidth / $ratio;
        }

        $targetFolder = $targetParams['newFolder'];
        $targetFilename = $targetParams['newFilename'];

        $targetFilePath = sprintf('%s/%s', $targetFolder, $targetFilename);
        $imagineFile = $this->imagine->open($originalFilePath);
        $imagineFile->resize(new Box($targetWidth, $targetHeight))->save($targetFilePath);

        return $targetFilename;
    }


}
