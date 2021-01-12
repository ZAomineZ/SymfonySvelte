<?php

namespace App\Helper;

use App\Entity\Project;

class ProjectHelper
{
    /**
     * @param array $projects
     * @return array
     */
    public function toArray(array $projects): array
    {
        $newArray = [];
        /** @var Project $project */
        foreach ($projects as $key => $project) {
            $newArray[$key]['id'] = $project->getId();
            $newArray[$key]['title'] = $project->getTitle();
            $newArray[$key]['slug'] = $project->getSlug();
            $newArray[$key]['content'] = $project->getContent();
            $newArray[$key]['category'] = $project->getCategory() ? $project->getCategory()->getName() : null;
            $newArray[$key]['validate'] = $project->getValidate();
            $newArray[$key]['created_at'] = $project->getCreatedAt()->format('Y-m-d h:i:s');
        }
        return $newArray;
    }

    /**
     * @param Project $project
     * @return array
     */
    public function toObject(Project $project): array
    {
        $newArray = [];
        // SET properties
        $newArray['id'] = $project->getId();
        $newArray['title'] = $project->getTitle();
        $newArray['slug'] = $project->getSlug();
        $newArray['content'] = $project->getContent();
        $newArray['category'] = $project->getCategory() ? $project->getCategory()->getSlug() : null;
        $newArray['validate'] = $project->getValidate();
        $newArray['created_at'] = $project->getCreatedAt()->format('Y-m-d h:i:s');
        // Return array
        return $newArray;
    }
}