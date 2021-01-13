<?php

namespace App\DataFixtures\Image;

use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ImagesFixtures extends Fixture
{

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 3; $i++) {
            $image = (new Image())
                ->setTitle('Image test ' . ($i + 1))
                ->setSlug('image-test-' . ($i + 1))
                ->setPath('/path/to/file-' . ($i + 1) . '.jpg');
            $manager->persist($image);
        }
        $manager->flush();
    }
}