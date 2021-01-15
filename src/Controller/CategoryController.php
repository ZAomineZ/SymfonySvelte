<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/categories', name: 'category.index')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig');
    }

    /**
     * @return Response
     */
    #[Route('/category/{slug}', name: 'category')]
    public function show(): Response
    {
        return $this->render('category/show.html.twig');
    }
}
