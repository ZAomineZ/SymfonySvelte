<?php

namespace App\DataFixtures\Tag;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagsFixtures extends Fixture
{

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 3; $i++) {
            $tag = new Tag();
            $tag->setName('Tag ' . ($i + 1));
            $tag->setSlug('tag-' . ($i + 1));
            $manager->persist($tag);
        }
        $manager->flush();
    }
}