<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    const CATEGORIES = [
      'PHP',
      'Java',
      'Javascript',
      'Ruby',
      'C#',
      'Devops',
      'C++',
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::CATEGORIES as $key => $categoryName) {
            $category = new Category();
            $category->setName(mb_strtolower($categoryName));
            $manager->persist($category);
            $this->addReference('categorie_'.$key, $category);
        }
        $manager->flush();
    }
}
