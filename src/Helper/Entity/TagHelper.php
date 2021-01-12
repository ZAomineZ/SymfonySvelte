<?php

namespace App\Helper\Entity;

use App\Entity\Tag;

class TagHelper
{
    /**
     * @param array $tags
     * @return array
     */
    public function toArray(array $tags): array
    {
        $newArray = [];
        /** @var Tag $tag */
        foreach ($tags as $key => $tag) {
            $newArray[$key]['id'] = $tag->getId();
            $newArray[$key]['name'] = $tag->getName();
            $newArray[$key]['slug'] = $tag->getSlug();
            $newArray[$key]['created_at'] = $tag->getCreatedAt()->format('Y-m-d h:i:s');
        }
        return $newArray;
    }

    /**
     * @param Tag $tag
     * @return array
     */
    public function toObject(Tag $tag): array
    {
        $newArray = [];
        // SET properties
        $newArray['id'] = $tag->getId();
        $newArray['name'] = $tag->getName();
        $newArray['slug'] = $tag->getSlug();
        $newArray['created_at'] = $tag->getCreatedAt()->format('Y-m-d h:i:s');
        // Return array
        return $newArray;
    }
}