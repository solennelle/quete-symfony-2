<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Service\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @param ArticleRepository $articleRepository
     * @Route("/", name="article_index", methods={"GET"})
     * @return Response
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAllWithCategoriesAndTags(),
        ]);
    }

    /**
     * @param Request $request
     * @param Slugify $slugify
     * @param \Swift_Mailer $mailer
     * @Route("/new", name="article_new", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_AUTHOR")
     */
    public function new(Request $request, Slugify $slugify, \Swift_Mailer $mailer): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $article->setSlug($slugify->generate($article->getTitle()));
            $author = $this->getUser();
            $article->setAuthor($author);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            $sender = getenv('MAILER_FROM_ADDRESS');
            $message = (new \Swift_Message('Un nouvel article vient d\'être publié !'))
                ->setFrom($sender)
                ->setTo('sol.swift.test@gmail.com')
                ->setBody($this->renderView(
                    'article/Email/notification.html.twig',
                    ['article' => $article]),
                    'TEXT/HTML'
                );
            ;
            $mailer->send($message);
            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Article $article
     * @Route("/{id}", name="article_show", methods={"GET"})
     * @return Response
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @param Request $request
     * @param Article $article
     * @param Slugify $slugify
     * @Route("/{id}/edit", name="article_edit", methods={"GET","POST"})
     * @return Response
     */
    public function edit(Request $request, Article $article, Slugify $slugify): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        if ($this->getUser() != $article->getAuthor() || $this->getUser() != $hasAccess) {
            $this->createAccessDeniedException();
        }
            $form = $this->createForm(ArticleType::class, $article);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $article->setSlug($slugify->generate($article->getTitle()));
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('article_index', [
                    'id' => $article->getId(),
                ]);
            }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Article $article
     * @Route("/{id}", name="article_delete", methods={"DELETE"})
     * @return Response
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index');
    }
}
