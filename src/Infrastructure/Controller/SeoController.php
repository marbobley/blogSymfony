<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Domain\Model\Component\SocialSeo;
use App\Domain\Model\SeoModel;
use App\Domain\UseCaseInterface\DeleteSeoInterface;
use App\Domain\UseCaseInterface\GetSeoByIdentifierInterface;
use App\Domain\UseCaseInterface\ListSeoInterface;
use App\Domain\UseCaseInterface\SaveSeoInterface;
use App\Infrastructure\Form\SeoType;
use App\Infrastructure\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/seo')]
class SeoController extends AbstractController
{
    #[Route('', name: 'app_seo_index', methods: ['GET'])]
    public function index(ListSeoInterface $listSeo): Response
    {
        return $this->render('seo/index.html.twig', [
            'seos' => $listSeo->execute(),
        ]);
    }

    #[Route('/new', name: 'app_seo_new', methods: ['GET', 'POST'])]
    public function create(Request $request, SaveSeoInterface $saveSeo, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(SeoType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var SeoModel $seoModel */
            $seoModel = $form->getData();

            $imageFile = $form->get('social')->get('ogImageFile')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $newSocial = new SocialSeo(
                    ogTitle: $seoModel->getSocial()->getOgTitle(),
                    ogDescription: $seoModel->getSocial()->getOgDescription(),
                    ogImage: 'uploads/seo/' . $imageFileName,
                    ogType: $seoModel->getSocial()->getOgType(),
                    twitterCard: $seoModel->getSocial()->getTwitterCard()
                );
                $seoModel = new SeoModel(
                    pageIdentifier: $seoModel->getPageIdentifier(),
                    core: $seoModel->getCore(),
                    social: $newSocial,
                    sitemap: $seoModel->getSitemap(),
                    meta: $seoModel->getMeta()
                );
            }

            $saveSeo->execute($seoModel);
            $this->addFlash('success', 'Configuration SEO créée avec succès !');
            return $this->redirectToRoute('app_seo_index');
        }

        return $this->render('seo/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{identifier}', name: 'app_seo_edit', methods: ['GET', 'POST'])]
    public function edit(
        string $identifier,
        Request $request,
        GetSeoByIdentifierInterface $getSeo,
        SaveSeoInterface $saveSeo,
        FileUploader $fileUploader
    ): Response {
        $seo = $getSeo->execute($identifier);
        if (!$seo) {
            throw $this->createNotFoundException('Configuration SEO non trouvée.');
        }

        $form = $this->createForm(SeoType::class, $seo, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var SeoModel $seoModel */
            $seoModel = $form->getData();

            $imageFile = $form->get('social')->get('ogImageFile')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $newSocial = new SocialSeo(
                    ogTitle: $seoModel->getSocial()->getOgTitle(),
                    ogDescription: $seoModel->getSocial()->getOgDescription(),
                    ogImage: 'uploads/seo/' . $imageFileName,
                    ogType: $seoModel->getSocial()->getOgType(),
                    twitterCard: $seoModel->getSocial()->getTwitterCard()
                );
                $seoModel = new SeoModel(
                    pageIdentifier: $seoModel->getPageIdentifier(),
                    core: $seoModel->getCore(),
                    social: $newSocial,
                    sitemap: $seoModel->getSitemap(),
                    meta: $seoModel->getMeta()
                );
            }

            $saveSeo->execute($seoModel);
            $this->addFlash('success', 'Configuration SEO mise à jour !');
            return $this->redirectToRoute('app_seo_index');
        }

        return $this->render('seo/edit.html.twig', [
            'form' => $form->createView(),
            'seo' => $seo,
        ]);
    }

    #[Route('/delete/{identifier}', name: 'app_seo_delete', methods: ['POST'])]
    public function delete(string $identifier, Request $request, DeleteSeoInterface $deleteSeo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$identifier, (string) $request->request->get('_token'))) {
            $deleteSeo->execute($identifier);
            $this->addFlash('success', 'Configuration SEO supprimée.');
        }

        return $this->redirectToRoute('app_seo_index');
    }
}
