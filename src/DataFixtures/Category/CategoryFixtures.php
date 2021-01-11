<?php

namespace App\DataFixtures\Category;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $category = (new Category())
            ->setName('Anime')
            ->setSlug('anime')
            ->setContent('Je suis une description pour la catégorie anime.');
        $manager->persist($category);
        $manager->flush();
    }
}