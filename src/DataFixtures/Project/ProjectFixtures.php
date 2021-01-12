<?php

namespace App\DataFixtures\Project;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProjectFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $project = (new Project())
            ->setTitle('Project SEO')
            ->setSlug('project-seo')
            ->setContent('Je vous propose un content sur le seo de mon entreprise !')
            ->setValidate(0);
        $manager->persist($project);
        $manager->flush();
    }
}
