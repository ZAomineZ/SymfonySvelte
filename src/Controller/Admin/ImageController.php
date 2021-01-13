<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route(path: '/admin/images', name: 'admin.image.index', methods: ["GET", "HEAD"])]
    public function index(): Response
    {
        return $this->render('page/admin/image/index.html.twig');
    }

    /**
     * @return Response
     */
    #[Route(path: '/admin/image/create', name: 'admin.image.create', methods: ["GET", "HEAD"])]
    public function create(): Response
    {
        return $this->render('page/admin/image/create.html.twig');
    }

    /**
     * @return Response
     */
    #[Route(path: '/admin/image/edit/{slug}', name: 'admin.image.edit', methods: ["GET", "HEAD"])]
    public function edit(): Response
    {
        return $this->render('page/admin/image/edit.html.twig');
    }
}