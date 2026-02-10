<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SiteMapController extends AbstractController
{
    #[Route('sitemap.xml', name: 'sitemap', format: 'xml')]
    public function index(): Response
    {
        $urls = [];
        $lastmod = (new \DateTime())->format('Y-m-d');

        $urls[] = [
            'loc' => $this->generateUrl('app_home', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'lastmod' => $lastmod,
            'changefreq' => 'monthly',
            'priority' => '1.0',
        ];

        $response = $this->render('sitemap/sitemap.xml.twig', [
            'urls' => $urls,
        ]);

        $response->headers->set('Content-Type', 'application/xml');

        return $response;
    }
}
