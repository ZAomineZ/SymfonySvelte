<?php

namespace App\DataFixtures\Project;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProjectValidateFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 3; $i++) {
            $project = (new Project())
                ->setTitle('Project SEO ' . $i)
                ->setSlug('project-seo-' . $i)
                ->setContent('Description project seo ' . $i)
                ->setValidate($i === 1 ? 0 : 1);
            $manager->persist($project);
        }
        $manager->flush();
    }
}