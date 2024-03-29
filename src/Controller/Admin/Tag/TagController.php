<?php

namespace App\Controller\Admin\Tag;

use App\Entity\Tag;
use App\Form\TagFormType;
use App\Repository\TagRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TagController extends AbstractController
{
    #[Route('/admin/tag', name: 'admin.tag.index')]
    public function index(TagRepository $tagRepository): Response
    {
        return $this->render('pages/admin/tag/index.html.twig', [
            "tags" => $tagRepository->findAll()
        ]);


    }

    #[Route('/admin/tag/create', name: 'admin.tag.create', methods: ['GET', 'POST'])]
    public function create(Request $request, TagRepository $tagRepository): Response
    {
        $tag = new Tag();

        $form = $this->createForm(TagFormType::class, $tag);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() )
        {
            $tagRepository->save($tag, true);
            $this->addFlash('success', "Votre tag a été ajouté avec succès.");
            return $this->redirectToRoute("admin.tag.index");
        }

        return $this->render('pages/admin/tag/create.html.twig', [
            "form" => $form->createView()
        ]);
    }

    #[Route('/admin/tag/{id<\d+>}/edit', name: 'admin.tag.edit', methods: ['GET', 'POST'])]
    public function edit(Tag $tag, Request $request, TagRepository $tagRepository) : Response
    {
        $form = $this->createForm(TagFormType::class, $tag);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() )
        {
            $tagRepository->save($tag, true);
            $this->addFlash('success', "Le tag a été modifié");
             return $this->redirectToRoute("admin.tag.index");
        }

        return $this->render("pages/admin/tag/edit.html.twig", [
            "form" => $form->createView(),
            "tag" => $tag
        ]);
    }

    #[Route('/admin/tag/{id<\d+>}/delete', name: 'admin.tag.delete', methods: ['POST'])]
    public function delete(Tag $tag, Request $request, TagRepository $tagRepository) : Response
    {
        if ( $this->isCsrfTokenValid('tag_' . $tag->getId(), $request->request->get('_csrf_token')) )
        {
            $tagRepository->remove($tag, true);
            $this->addFlash('success', "Le tag " . $tag->getName() ." a été supprimé.");
        }

        return $this->redirectToRoute('admin.tag.index');
    }
}
