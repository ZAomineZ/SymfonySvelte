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
}