<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RobotTxtController extends AbstractController
{
    #[Route('robots.txt', name: 'robots', format: 'txt')]
    public function robotsAction(): Response
    {
        $sitemap = $this->generateUrl('sitemap', [], UrlGeneratorInterface::ABSOLUTE_URL);

        return $this->render('robots/robots.txt.twig', [
            'sitemap' => $sitemap,
        ]);
    }
}
