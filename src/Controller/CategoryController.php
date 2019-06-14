<?php
// src/Controller/BlogController.php
namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


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
     * @Route("/category/new", name="category_index")
     * @IsGranted("ROLE_ADMIN")
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
