<?php

namespace App\Controller\API\Admin\Module;

use App\Entity\Project;

trait Taggable
{
    /**
     * @param array|null $tags
     * @return bool
     */
    public function allTagsExist(?array $tags = []): bool
    {
        $success = true;
        foreach ($tags as $tag) {
            $tag_exist = $this->tagRepository->findByName($tag);
            if (!$tag_exist) $success = false;
        }
        return $success;
    }

    /**
     * @param Project|null $project
     * @param array|null $tags
     */
    public function addAllTags(?Project $project, ?array $tags = []): void
    {
        array_map(function ($tag) use ($project) {
            $tag = $this->tagRepository->findByName($tag);
            $project->addTag($tag);
        }, $tags);
    }
}