<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route("/signin")]
    public function signin(): Response
    {
        return $this->render('auth/signin.html.twig');
    }

    /**
     * @return Response
     */
    #[Route("/signup")]
    public function signup(): Response
    {
        return $this->render('auth/signup.html.twig');
    }
}