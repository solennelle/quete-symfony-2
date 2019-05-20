<?php
// src/Controller/BlogController.php
namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class CategoryController extends AbstractController
{
    /**
     */
    public function index()
    {
        return $this->render('category.html.twig');
    }

    /**
     * @param Request $request
     * @Route("/category", name="category_index")
     * @return
     */
    public function add(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em -> persist($data);
            $em -> flush();
            return $this->redirectToRoute('app_index');
        }
        return $this->render('category.html.twig' , [
                'form' => $form->createView(),
            ]
        );
    }
}
