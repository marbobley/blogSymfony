<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Model\TagModel;
use App\Application\Factory\TagModelFactory;
use App\Application\UseCase\GetTag;
use App\Application\UseCaseInterface\CreateTagInterface;
use App\Application\UseCaseInterface\DeleteTagInterface;
use App\Application\UseCaseInterface\GetTagBySlugInterface;
use App\Application\UseCaseInterface\ListTagsInterface;
use App\Application\UseCaseInterface\UpdateTagInterface;
use App\Infrastructure\Form\TagType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    #[Route('/tags', name: 'app_tag_index', methods: ['GET'])]
    public function index(ListTagsInterface $listTags): Response
    {
        return $this->render('tag/index.html.twig', [
            'tags' => $listTags->execute(),
        ]);
    }

    #[Route('/tag/new', name: 'app_tag_new', methods: ['GET', 'POST'])]
    public function create(Request $request, CreateTagInterface $createTag): Response
    {
        $form = $this->createForm(TagType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var TagModel $tagDTO */
            $tagDTO = $form->getData();
            $createTag->execute($tagDTO);
            $this->addFlash('success', 'Tag créé avec succès !');
            return $this->redirectToRoute('app_tag_index');
        }

        return $this->render('tag/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/tag/{slug}', name: 'app_tag_show', methods: ['GET'])]
    public function show(string $slug, GetTagBySlugInterface $getTagBySlug, \App\Application\UseCaseInterface\ListPostsInterface $listPosts): Response
    {
        $tag = $getTagBySlug->execute($slug);
        return $this->render('tag/show.html.twig', [
            'tag' => $tag,
            'posts' => $listPosts->execute($tag->getId()),
        ]);
    }

    #[Route('/tag/edit/{id}', name: 'app_tag_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, GetTag $getTagUseCase, UpdateTagInterface $updateTag): Response
    {
        $tagEntity = $getTagUseCase->getById($id);
        $tagDTO = TagModelFactory::createFromEntity($tagEntity);

        $form = $this->createForm(TagType::class, $tagDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var TagModel $dto */
            $dto = $form->getData();
            $updateTag->execute($id, $dto);
            $this->addFlash('success', 'Tag mis à jour avec succès !');
            return $this->redirectToRoute('app_tag_index');
        }

        return $this->render('tag/edit.html.twig', [
            'form' => $form->createView(),
            'tag' => $tagEntity,
        ]);
    }

    #[Route('/tag/delete/{id}', name: 'app_tag_delete', methods: ['POST'])]
    public function delete(int $id, Request $request, DeleteTagInterface $deleteTag): Response
    {
        if ($this->isCsrfTokenValid('delete'.$id, (string) $request->request->get('_token'))) {
            $deleteTag->execute($id);
            $this->addFlash('success', 'Le tag a été supprimé.');
        }

        return $this->redirectToRoute('app_tag_index');
    }
}
