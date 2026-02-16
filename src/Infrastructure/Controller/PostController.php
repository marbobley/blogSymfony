<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Domain\Criteria\PostCriteria;
use App\Domain\Model\PostModel;
use App\Domain\UseCase\GetPost;
use App\Domain\UseCaseInterface\CreatePostInterface;
use App\Domain\UseCaseInterface\DeletePostInterface;
use App\Domain\UseCaseInterface\GetPostBySlugInterface;
use App\Domain\UseCaseInterface\ListAllPostsInterface;
use App\Domain\UseCaseInterface\ListPublishedPostsInterface;
use App\Domain\UseCaseInterface\UpdatePostInterface;
use App\Infrastructure\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PostController extends AbstractController
{
    #[Route('/post/preview', name: 'app_post_preview', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function preview(Request $request): Response
    {
        $form = $this->createForm(PostType::class);
        $form->handleRequest($request);

        /** @var PostModel $post */
        $post = $form->getData();

        if (null === $post->getCreatedAt()) {
            $post->setCreatedAt(new \DateTimeImmutable());
        }

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'preview' => true,
        ]);
    }

    #[Route('/posts', name: 'app_post_index', methods: ['GET'])]
    public function index(Request $request, ListPublishedPostsInterface $listPosts): Response
    {
        $search = $request->query->get('q');
        $lists = $listPosts->execute(new PostCriteria(search: (string) $search));
        return $this->render('post/index.html.twig', [
            'posts' => $lists,
        ]);
    }

    #[Route('/admin/posts', name: 'app_post_admin_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function adminIndex(ListAllPostsInterface $listPosts): Response
    {
        $lists = $listPosts->execute(new PostCriteria());
        return $this->render('post/admin_index.html.twig', [
            'posts' => $lists,
        ]);
    }

    #[Route('/post/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request, CreatePostInterface $createPost): Response
    {
        $form = $this->createForm(PostType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var PostModel $post */
            $post = $form->getData();
            $createPost->execute($post);
            $this->addFlash('success', 'Votre article a été enregistré avec succès !');

            if ($form->get('saveAndContinue')->isClicked()) {
                return $this->redirectToRoute('app_post_edit', ['id' => $post->getId()]);
            }

            return $this->redirectToRoute('app_post_admin_index');
        }

        return $this->render('post/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/post/{slug}', name: 'app_post_show', methods: ['GET'])]
    public function show(string $slug, GetPostBySlugInterface $getPostBySlug): Response
    {
        $post = $getPostBySlug->execute($slug);

        if (!$post->isPublished()) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Cet article est un brouillon.');
        }

        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/post/edit/{id}', name: 'app_post_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
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

                if ($form->get('saveAndContinue')->isClicked()) {
                    return $this->redirectToRoute('app_post_edit', ['id' => $id]);
                }

                return $this->redirectToRoute('app_post_admin_index');
            }

            $this->addFlash('error', 'Il y a des erreurs dans votre formulaire. Veuillez les corriger.');
        }

        return $this->render('post/edit.html.twig', [
            'form' => $form->createView(),
            'post' => $postModel,
        ]);
    }

    #[Route('/post/delete/{id}', name: 'app_post_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(int $id, Request $request, DeletePostInterface $deletePost): Response
    {
        if ($this->isCsrfTokenValid('delete'.$id, (string) $request->request->get('_token'))) {
            $deletePost->execute($id);
            $this->addFlash('success', 'L\'article a été supprimé.');
        }

        return $this->redirectToRoute('app_post_admin_index');
    }
}
