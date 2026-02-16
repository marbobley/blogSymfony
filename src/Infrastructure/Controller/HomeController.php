<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Domain\UseCaseInterface\ListPublishedPostsInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(ListPublishedPostsInterface $listPosts): Response
    {
        return $this->render('home/index.html.twig', [
            'posts' => $listPosts->execute(),
        ]);
    }
}
