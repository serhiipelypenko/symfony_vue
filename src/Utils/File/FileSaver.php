<?php

namespace App\Utils\File;

use App\Utils\Filesystem\FilesystemWorker;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

readonly class FileSaver
{
    public function __construct(
        private SluggerInterface $slugger,
        private string $uploadsTempDir,
        private FilesystemWorker $filesystemWorker){

    }

    public function saveUploadFileIntoTemp(UploadedFile $uploadedFile): ?string
    {
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $filename = sprintf('%s-%s.%s', $safeFilename, uniqid(), $uploadedFile->guessExtension());

       $this->filesystemWorker->createFolderIfItNotExist($this->uploadsTempDir);

        try{
            $uploadedFile->move($this->uploadsTempDir, $filename);
        } catch ( \Exception $exception) {
            return null;
        }

        return $filename;
    }


}
