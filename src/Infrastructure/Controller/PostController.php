<?php

namespace App\Infrastructure\Controller;

use App\Application\DTO\PostDTO;
use App\Application\UseCase\CreatePost;
use App\Application\UseCaseInterface\CreatePostInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function create(Request $request, CreatePostInterface $createPost): Response
    {
        if ($request->isMethod('POST')) {
            $dto = new PostDTO(
                $request->request->get('title'),
                $request->request->get('content')
            );

            $createPost->execute($dto);

            return $this->redirectToRoute('app_post_new'); // À changer vers une liste de posts plus tard
        }

        return $this->render('post/create.html.twig');
    }
}
