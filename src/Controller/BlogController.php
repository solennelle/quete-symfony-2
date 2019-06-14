<?php
// src/Controller/BlogController.php
namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Tag;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * Show all row from article's entity
     *
     * @param ArticleRepository $articleRepository
     * @Route("/blog", name="blog_index")
     * @return Response A response instance
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAllWithCategories();
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
     * @param string $slug
     *
     * @Route("/blog/{slug}",
     *     defaults={"slug" = null},
     *     name="blog_show")
     * @return Response A response instance
     */
    public function show(?string $slug): Response
    {
        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findOneBy(['slug' => $slug]);

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
     *     methods={"GET"},
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
