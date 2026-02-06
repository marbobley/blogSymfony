<?php

namespace App\Infrastructure\Controller;

use App\Application\DTO\PostDTO;
use App\Application\UseCaseInterface\CreatePostInterface;
use App\Application\UseCaseInterface\ListPostsInterface;
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
        if ($request->isMethod('POST')) {
            $dto = new PostDTO(
                $request->request->get('title'),
                $request->request->get('content')
            );

            $createPost->execute($dto);

            return $this->redirectToRoute('app_post_index');
        }

        return $this->render('post/create.html.twig');
    }
}
