<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route(path: '/admin/projects', name: 'admin.project.index', methods: ["GET", "HEAD"])]
    public function index(): Response
    {
        return $this->render('page/admin/project/index.html.twig');
    }

    /**
     * @return Response
     */
    #[Route(path: '/admin/project/create', name: 'admin.project.create', methods: ["GET", "HEAD"])]
    public function create(): Response
    {
        return $this->render('page/admin/project/create.html.twig');
    }

    /**
     * @return Response
     */
    #[Route(path: '/admin/project/edit/{id}', name: 'admin.project.edit', methods: ["GET", "HEAD"])]
    public function edit(): Response
    {
        return $this->render('page/admin/project/edit.html.twig');
    }
}