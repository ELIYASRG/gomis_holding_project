<?php

namespace App\Controller\Visitor\AboutMe;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutMeController extends AbstractController
{
    #[Route('/about-me', name: 'visitor.about_me.index')]
    public function index(): Response
    {
        return $this->render('pages/visitor/about_me/index.html.twig');
    }

    #[Route('/user/about-me', name: 'user.about_me.index')]
    public function indexx(): Response
    {
        return $this->render('pages/user/about_me/index.html.twig');
    }
}
