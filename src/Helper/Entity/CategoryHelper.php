<?php

namespace App\Helper\Entity;

use App\Entity\Category;

class CategoryHelper
{
    /**
     * @param array $categories
     * @return array
     */
    public function toArray(array $categories): array
    {
        $newArray = [];
        /** @var Category $category */
        foreach ($categories as $key => $category) {
            $newArray[$key]['id'] = $category->getId();
            $newArray[$key]['name'] = $category->getName();
            $newArray[$key]['slug'] = $category->getSlug();
            $newArray[$key]['content'] = $category->getContent();
            $newArray[$key]['created_at'] = $category->getCreatedAt()->format('Y-m-d h:i:s');
        }
        return $newArray;
    }

    /**
     * @param Category $category
     * @return array
     */
    public function toObject(Category $category): array
    {
        $newArray = [];
        // SET properties
        $newArray['id'] = $category->getId();
        $newArray['name'] = $category->getName();
        $newArray['slug'] = $category->getSlug();
        $newArray['content'] = $category->getContent();
        $newArray['created_at'] = $category->getCreatedAt()->format('Y-m-d h:i:s');
        // Return array
        return $newArray;
    }
}