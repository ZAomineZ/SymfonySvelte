<?php

namespace App\DataFixtures\Image;

use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageDeleteFixtures extends Fixture
{

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $path = dirname(__DIR__, 3) . '/tests/public/uploads/images/test-delete.jpg';
        $uploadFile = new UploadedFile($path, 'test-delete.jpg', '/image/png');

        $image = (new Image())
            ->setTitle('Image valid')
            ->setSlug('image-valid')
            ->setPath($path)
            ->setFile($uploadFile);
        $manager->persist($image);
        $manager->flush();
    }
}