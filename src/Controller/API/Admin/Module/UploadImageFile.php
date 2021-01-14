<?php

namespace App\Controller\API\Admin\Module;

use App\Entity\Image;
use Cocur\Slugify\Slugify;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait UploadImageFile
{

    /**
     * @var string|null
     */
    private ?string $filename = null;

    /**
     * @param UploadedFile|null $file
     */
    public function uploadFile(?UploadedFile $file = null)
    {
        if (!$file) {
            return;
        }

        // Try move file to directory
        $directory = $this->getParameter($this->getEnv() === 'dev' ? 'images_directory' : 'images_tests_directory');
        $file->move($directory, $this->getFilename());
    }

    /**
     * @param string|null $path
     */
    public function removeFile(?string $path = null): void
    {
        if (!file_exists($path)) {
            return;
        }
        unlink($path);
    }

    /**
     * @param Image $image
     * @param UploadedFile|null $file
     */
    public function setFileImageEntity(Image $image, ?UploadedFile $file = null)
    {
        if (!$file) {
            return;
        }

        // Create filename path
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $slugFilename = (new Slugify())->slugify($originalFilename);
        $this->filename = $slugFilename . '-' . uniqid() . '.' . $file->guessExtension();

        // Set property path to image entity
        $image->setPath($this->getFilename());
    }

    /**
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @return string|null
     */
    private function getEnv(): ?string
    {
        return $this->environment->getEnv();
    }
}