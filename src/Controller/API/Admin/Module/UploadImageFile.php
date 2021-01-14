<?php

namespace App\Controller\API\Admin\Module;

use App\Entity\Image;
use Cocur\Slugify\Slugify;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait UploadImageFile
{
    /**
     * @param UploadedFile|null $file
     * @param Image|null $image
     */
    public function uploadFile(?UploadedFile $file = null, ?Image $image = null)
    {
        if ($file) {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $slugFilename = (new Slugify())->slugify($originalFilename);
            $newFilename = $slugFilename . '-' . uniqid() . '.' . $file->guessExtension();

            // Try move file to directory
            $directory = $this->getParameter($this->getEnv() === 'dev' ? 'images_directory' : 'images_tests_directory');
            $file->move($directory, $newFilename);

            // Set property path to image entity
            $image->setPath($newFilename);
        }
    }

    /**
     * @return string|null
     */
    private function getEnv(): ?string
    {
        return $this->environment->getEnv();
    }
}