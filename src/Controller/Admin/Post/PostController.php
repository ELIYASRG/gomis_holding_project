<?php

namespace App\Controller\Admin\Post;

use App\Entity\Post;
use App\Entity\Image;
use App\Form\PostFormType;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    #[Route('/admin/post', name: 'admin.post.index')]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('pages/admin/post/index.html.twig', [
            "posts" => $postRepository->findAll()
        ]);
    }


    #[Route('/admin/post/create', name: 'admin.post.create')]
    public function create(Request $request, PostRepository $postRepository, ManagerRegistry $doctrine): Response
    {
        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);
        
        
        $form->handleRequest($request);
        // $entityManager = $doctrine->getManager()->getManager();
        // if ($form)
        // {
        
        //     $image = new Image();
        //     $image->setName();
        // }
        
        if ($form->isSubmitted() && $form->isValid() )
        {
            // EXCEPTION(1): création d'une nouvelle instance pour setter la propriété Images(Collection) de Post
            // $image = new Image();
            // $image->setName('cool');
            // $post->addImage($image);
            
            
            // La propriété fictive imageFile à bien reçu une valeur à partir du formulaire
            // dd($post->getImageFile());
            
            // même chose qu'au-dessus d'une autre manière
            // $imageFile = $form->get('imageFile')->getData();
            // dd($imageFile);
            
            // cependant la propriété Images(Collection) reste vide
            // dd($post->getImages());
            
            // EXCEPTION(2): Dans le cas de l'instanciation de la classe Image
            // dd($post->getImages());
            // dd($post->getImages()->getIterator());
            
            // /!\ return: The property "images" in class "App\Entity\Post" can be defined with the methods "addImage()", "removeImage()" but the new value must be an array or an instance of \Traversable.

            // SOLUTION : la propriété $imageFile de $$@type File@$$ ne remplir la propriété Images de type collection qui elle-mm vise l'entité Image.

            $post->setUser($this->getUser());
            
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
    
    #[Route('/admin/post/{id<[0-9]+>}/publish', name: 'admin.post.publish', methods: ['POST'])]
    public function publish(Post $post, PostRepository $postRepository) : Response
    {
        if ($post->isIsPublished() == false)
        {
            $post->setIsPublished(true);
            $post->setPublishedAt(new \DateTimeImmutable('now'));
            $postRepository->save($post, true);
            $this->addFlash("success", "Votre article vient d'être publié");
        }
        else {
            $post->setIsPublished(false);
            $post->setPublishedAt(null);
            $postRepository->save($post, true);
            $this->addFlash("success", "Votre article vient d'être retiré de la liste de publication");
        }
        return $this->redirectToRoute('admin.post.index');
        
    }
    
    #[Route('/admin/post/{id<[0-9]+>}/edit', name: 'admin.post.edit')]
    public function edit(Post $post, 
    Request $request, 
    PostRepository $postRepository, 
    CategoryRepository $categoryRepository) 
    {
        if ( ! $categoryRepository->findAll() )
        {
            $this->addFlash("warning", "Vous devez créer au moins une catégorie avant de rédiger des articles");
            return $this->redirectToRoute('admin.category.index');
        }
        
        $form = $this->createForm(PostFormType::class, $post);
        
        $form->handleRequest($request);
        
        if ( $form->isSubmitted() && $form->isValid() )
        {
            $post->setUser($this->getUser());
            $postRepository->save($post, true);
            $this->addFlash("success", "L'article numéro " . $post->getId() . " a été modifié");
            return $this->redirectToRoute("admin.post.index");
        }

        return $this->render('pages/admin/post/edit.html.twig', [
            "form" => $form->createView(),
            "post" => $post
        ]);
    }
    
    #[Route('/admin/post/{id<[0-9]+>}/delete', name: 'admin.post.delete')]
    public function delete(Post $post, Request $request, PostRepository $postRepository) : Response
    {
        if ( $this->isCsrfTokenValid('post_' . $post->getId(), $request->request->get('_csrf_token')) ) 
        {
            $this->addFlash("success", "L'article numéro " . $post->getId() . " a été supprimé.");
            $postRepository->remove($post, true);
        }

        return $this->redirectToRoute("admin.post.index");
    }
    
}
