<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Tag;
use App\Entity\Article;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 1000; $i++) {
            $category = new Category();
            $category->setName("category " . $i);
            $manager->persist($category);

            $tag = new Tag();
            $tag->setName("tag " . $i);
            $manager->persist($tag);

            $article = new Article();
            $slugify = new Slugify();
            $article->setTitle("article " . $i);
            $article->setContent("article " . $i . " content");
            $article->setCategory($category);
            $article->addTag($tag);
            $article->setSlug($slugify->generate($article->getTitle()));
            $manager->persist($article);
        }
        $manager->flush();
    }
}
