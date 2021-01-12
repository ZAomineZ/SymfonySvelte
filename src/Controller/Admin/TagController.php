<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route(path: '/admin/tags', name: 'admin.tags.index', methods: ["GET", "HEAD"])]
    public function index(): Response
    {
        return $this->render('page/admin/tag/index.html.twig');
    }

    /**
     * @return Response
     */
    #[Route(path: '/admin/tag/create', name: 'admin.tag.create', methods: ["GET", "HEAD"])]
    public function create(): Response
    {
        return $this->render('page/admin/tag/create.html.twig');
    }

    /**
     * @return Response
     */
    #[Route(path: '/admin/tag/edit/{id}', name: 'admin.tag.edit', methods: ["GET", "HEAD"])]
    public function edit(): Response
    {
        return $this->render('page/admin/tag/edit.html.twig');
    }
}