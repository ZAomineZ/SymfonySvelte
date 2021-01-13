<?php

namespace App\Helper\Entity;

use App\Entity\Image;

class ImageHelper
{
    /**
     * @param array $images
     * @return array
     */
    public function toArray(array $images): array
    {
        $newArray = [];
        /** @var Image $image */
        foreach ($images as $key => $image) {
            $newArray[$key]['id'] = $image->getId();
            $newArray[$key]['title'] = $image->getTitle();
            $newArray[$key]['slug'] = $image->getSlug();
            $newArray[$key]['path'] = $image->getPath();
            $newArray[$key]['created_at'] = $image->getCreatedAt()->format('Y-m-d h:i:s');
        }
        return $newArray;
    }

    /**
     * @param Image $image
     * @return array
     */
    public function toObject(Image $image): array
    {
        $newArray = [];
        // SET properties
        $newArray['id'] = $image->getId();
        $newArray['title'] = $image->getTitle();
        $newArray['slug'] = $image->getSlug();
        $newArray['path'] = $image->getPath();
        $newArray['created_at'] = $image->getCreatedAt()->format('Y-m-d h:i:s');
        // Return array
        return $newArray;
    }
}