<?php

namespace App\Controller\Admin\Post;

use App\Entity\Post;
use App\Entity\Image;
use App\Form\PostFormType;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
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
    public function create(Request $request, PostRepository $postRepository, ManagerRegistry $doctrine): Response
    {
        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);
        
        
        $form->handleRequest($request);
        // $entityManager = $doctrine->getManager()->getManager();
        // if ($form->)
        // {
        //     $image = new Image();
        // }

        if ($form->isSubmitted() && $form->isValid() )
        {
            $post->setUser($this->getUser());
            // $post->addImage($image);
            
            // dd($post);
            $postRepository->save($post, true);
            $this->addFlash('success', "Votre article a été créé avec succès.");
            return $this->redirectToRoute('admin.post.index');
        }
        // $entityManager->persist($image);
        // $entityManager->flush();

        return $this->render('pages/admin/post/create.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
