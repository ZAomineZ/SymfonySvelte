<?php

namespace App\DataFixtures\Image;

use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageWithPathValidFixtures extends Fixture
{

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $path = dirname(__DIR__, 3) . '/tests/public/uploads/images/test-update.jpg';
        $uploadFile = new UploadedFile($path, 'test-update.jpg', '/image/png');

        $image = (new Image())
            ->setTitle('Image valid')
            ->setSlug('image-valid')
            ->setPath($path)
            ->setFile($uploadFile);
        $manager->persist($image);
        $manager->flush();
    }
}