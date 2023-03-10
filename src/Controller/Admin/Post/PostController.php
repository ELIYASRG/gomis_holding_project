<?php

namespace App\Controller\Admin\Post;

use App\Entity\Post;
use App\Form\PostFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    #[Route('/admin/post', name: 'admin.post.index')]
    public function index(): Response
    {
        return $this->render('pages/admin/post/index.html.twig');
    }


    #[Route('/admin/post/create', name: 'admin.post.create')]
    public function create(): Response
    {
        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);

        return $this->render('pages/admin/post/create.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
