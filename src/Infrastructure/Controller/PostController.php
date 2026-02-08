<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Domain\Model\PostModel;
use App\Domain\UseCase\GetPost;
use App\Domain\UseCaseInterface\CreatePostInterface;
use App\Domain\UseCaseInterface\DeletePostInterface;
use App\Domain\UseCaseInterface\GetPostBySlugInterface;
use App\Domain\UseCaseInterface\ListPostsInterface;
use App\Domain\UseCaseInterface\UpdatePostInterface;
use App\Infrastructure\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
    #[Route('/posts', name: 'app_post_index', methods: ['GET'])]
    public function index(ListPostsInterface $listPosts): Response
    {
        $lists = $listPosts->execute();
        return $this->render('post/index.html.twig', [
            'posts' => $lists,
        ]);
    }

    #[Route('/post/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function create(Request $request, CreatePostInterface $createPost): Response
    {
        $form = $this->createForm(PostType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var PostModel $post */
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
    public function edit(int $id, Request $request, GetPost $getPostUseCase, UpdatePostInterface $updatePost): Response
    {
        $postModel = $getPostUseCase->execute($id);

        $form = $this->createForm(PostType::class, $postModel);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                /** @var PostModel $dto */
                $dto = $form->getData();

                $updatePost->execute($id, $dto);

                $this->addFlash('success', 'Votre article a été mis à jour avec succès !');

                return $this->redirectToRoute('app_post_index');
            }

            $this->addFlash('error', 'Il y a des erreurs dans votre formulaire. Veuillez les corriger.');
        }

        return $this->render('post/edit.html.twig', [
            'form' => $form->createView(),
            'post' => $postModel,
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
