<?php


namespace App\DataFixtures\Project;


use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProjectsFixtures extends Fixture
{

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $project = (new Project())
                ->setTitle('Project ' . ($i + 1))
                ->setSlug('project-' . ($i + 1))
                ->setContent('Je suis une description pour le project ' . ($i + 1))
                ->setValidate(0);
            $manager->persist($project);
        }
        $manager->flush();
    }
}