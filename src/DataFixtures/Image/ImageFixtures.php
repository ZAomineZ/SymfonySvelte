<?php

namespace App\DataFixtures\Image;

use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ImageFixtures extends Fixture
{

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $image = (new Image())
            ->setTitle('Image test')
            ->setSlug('image-test')
            ->setPath('/path/to/file.jpg');
        $manager->persist($image);
        $manager->flush();
    }
}