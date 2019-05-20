<?php
// src/Controller/BlogController.php
namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * Show all row from article's entity
     *
     * @Route("/blog", name="blog_index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        if (!$articles) {
            throw $this->createNotFoundException(
                'No article found in article\'s table.'
            );
        }

        return $this->render(
            'blog/index.html.twig',
            ['articles' => $articles]
        );
    }


    /**
     * Getting a article with a formatted slug for title
     *
     * @param string $slug The slugger
     *
     * @Route("/blog/{slug<[a-z0-9-]+>}",
     *     defaults={"slug" = null},
     *     name="blog_show")
     * @return Response A response instance
     */
    public function show(?string $slug): Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find an article in article\'s table.');
        }

        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );

        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article with ' . $slug . ' title, found in article\'s table.'
            );
        }

        return $this->render(
            'blog/show.html.twig',
            [
                'article' => $article,
                'slug' => $slug,
            ]
        );
    }

    /**
     * Getting a category
     *
     * @param Category $category
     *
     * @Route("blog/category/{name<^[a-z0-9-]+$>}",
     *     defaults={"slug" = null},
     *     name="category_show")
     * @return Response A response instance
     */
    public function showByCategory(Category $category): Response
    {
        $articles = $category->getArticles();
        return $this->render(
            'blog/category.html.twig',
            [
                'articles' => $articles,
                'category' => $category,
            ]
        );
    }

    /**
     * Getting a tag and associated articles
     *
     * @param Tag $tag
     *
     * @Route("blog/tag/{name}",
     *     defaults={"slug" = null},
     *     requirements={"name"="[a-zA-Z0-9_\/\s.-]+"},
     *     name="tag_show")
     * @return Response A response instance
     */
    public function showByTag(Tag $tag): Response
    {
        $articles = $tag->getArticles();
        return $this->render(
            'blog/tag.html.twig',
            [
                'articles' => $articles,
                'tag' => $tag,
            ]
        );
    }

}
