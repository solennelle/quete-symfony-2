<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 50; $i++) {
            $article = new Article();
            $article->setTitle(mb_strtolower($faker->realText($maxNbChars = 40, $indexSize = 2)));
            $article->setContent(mb_strtolower($faker->realText($maxNbChars = 350, $indexSize = 2)));
            $article->setCategory($this->getReference('categorie_'.$faker->numberBetween($min = 0, $max = 6)));
            $manager->persist($article);
        }
        $manager->flush();
    }
}
