<?php
// src/Controller/BlogController.php
namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog_index")
     */
    public function index()
    {
        return $this->render('blog/index.html.twig', [
            'owner' => 'Solène'
        ]);
    }

    /**
     * @Route("/blog/show/{page}",
     *     requirements={"page"="[a-z0-9-]+"},
     *     methods={"GET"},
     *     defaults={"page"= "Article sans titre"},
     *     name="blog_list"
     * )
     */

    public function show($page)
    {
        $page = ucwords(str_replace("-"," ",$page));
        return $this->render('blog/show.html.twig', ['page' => $page]);

    }
}
