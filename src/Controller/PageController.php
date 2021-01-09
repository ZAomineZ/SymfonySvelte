<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('page/index.html.twig');
    }

    /**
     * @return Response
     */
    #[Route('/admin', name: 'admin')]
    public function admin(): Response
    {
        return $this->render('page/admin/admin.html.twig');
    }
}
