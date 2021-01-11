<?php

namespace App\DataFixtures\Category;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoriesFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $category = (new Category())
                ->setName('Category ' . ($i + 1))
                ->setSlug('Category-' . ($i + 1))
                ->setContent('Je suis une description de la Category ' . ($i + 1));
            $manager->persist($category);
        }
        $manager->flush();
    }
}