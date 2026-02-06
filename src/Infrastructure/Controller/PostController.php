<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\DTO\PostDTO;
use App\Application\UseCaseInterface\CreatePostInterface;
use App\Application\UseCaseInterface\ListPostsInterface;
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
            /** @var PostDTO $dto */
            $dto = $form->getData();

            $createPost->execute($dto);

            $this->addFlash('success', 'Votre article a été publié avec succès !');

            return $this->redirectToRoute('app_post_index');
        }

        return $this->render('post/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
