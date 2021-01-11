<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route("/admin/categories", methods: ["GET", "HEAD"])]
    public function index(): Response
    {
        return $this->render('page/admin/category/index.html.twig');
    }

    /**
     * @return Response
     */
    #[Route("/admin/category/create", methods: ["GET", "HEAD"])]
    public function create(): Response
    {
        return $this->render('page/admin/category/create.html.twig');
    }

    /**
     * @return Response
     */
    #[Route("/admin/category/create", methods: ["GET", "HEAD"])]
    public function edit(): Response
    {
        return $this->render('page/admin/category/edit.html.twig');
    }
}