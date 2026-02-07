<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\DTO\PostDTO;
use App\Application\Factory\PostDTOFactory;
use App\Application\UseCaseInterface\CreatePostInterface;
use App\Application\UseCaseInterface\DeletePostInterface;
use App\Application\UseCaseInterface\GetPostBySlugInterface;
use App\Application\UseCaseInterface\GetPostInterface;
use App\Application\UseCaseInterface\ListPostsInterface;
use App\Application\UseCaseInterface\UpdatePostInterface;
use App\Infrastructure\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/posts', name: 'app_post_index', methods: ['GET'])]
    public function index(ListPostsInterface $listPosts): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $listPosts->execute(),
        ]);
    }

    #[Route('/post/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function create(Request $request, CreatePostInterface $createPost): Response
    {
        $form = $this->createForm(PostType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var PostDTO $post */
            $post = $form->getData();
            $createPost->execute($post);
            $this->addFlash('success', 'Votre article a été publié avec succès !');
            return $this->redirectToRoute('app_post_index');
        }

        return $this->render('post/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/post/{slug}', name: 'app_post_show', methods: ['GET'])]
    public function show(string $slug, GetPostBySlugInterface $getPostBySlug): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $getPostBySlug->execute($slug),
        ]);
    }

    #[Route('/post/edit/{id}', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, \App\Application\UseCase\GetPost $getPostUseCase, UpdatePostInterface $updatePost): Response
    {
        $postEntity = $getPostUseCase->getById($id);
        $postResponseDTO = \App\Application\Factory\PostResponseDTOFactory::createFromEntity($postEntity);

        $postDTO = PostDTOFactory::createFromEntity($postEntity);

        $form = $this->createForm(PostType::class, $postDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                /** @var PostDTO $dto */
                $dto = $form->getData();

                $updatePost->execute($id, $dto);

                $this->addFlash('success', 'Votre article a été mis à jour avec succès !');

                return $this->redirectToRoute('app_post_index');
            }

            $this->addFlash('error', 'Il y a des erreurs dans votre formulaire. Veuillez les corriger.');
        }

        return $this->render('post/edit.html.twig', [
            'form' => $form->createView(),
            'post' => $postResponseDTO,
        ]);
    }

    #[Route('/post/delete/{id}', name: 'app_post_delete', methods: ['POST'])]
    public function delete(int $id, Request $request, DeletePostInterface $deletePost): Response
    {
        if ($this->isCsrfTokenValid('delete'.$id, (string) $request->request->get('_token'))) {
            $deletePost->execute($id);
            $this->addFlash('success', 'L\'article a été supprimé.');
        }

        return $this->redirectToRoute('app_post_index');
    }
}
