<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Domain\Criteria\PostCriteria;
use App\Domain\Model\TagModel;
use App\Domain\UseCase\GetTag;
use App\Domain\UseCaseInterface\CreateTagInterface;
use App\Domain\UseCaseInterface\DeleteTagInterface;
use App\Domain\UseCaseInterface\GetTagBySlugInterface;
use App\Domain\UseCaseInterface\ListTagsInterface;
use App\Domain\UseCaseInterface\ListPublishedPostsInterface;
use App\Domain\UseCaseInterface\UpdateTagInterface;
use App\Infrastructure\Form\TagType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
    #[IsGranted('ROLE_ADMIN')]
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
    public function show(string $slug, Request $request, GetTagBySlugInterface $getTagBySlug, ListPublishedPostsInterface $listPosts): Response
    {
        $tag = $getTagBySlug->execute($slug);
        $search = $request->query->get('q');
        return $this->render('tag/show.html.twig', [
            'tag' => $tag,
            'posts' => $listPosts->execute(new PostCriteria(tagId: $tag->getId(), search: (string) $search)),
        ]);
    }

    #[Route('/tag/edit/{id}', name: 'app_tag_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(int $id, Request $request, GetTag $getTagUseCase, UpdateTagInterface $updateTag): Response
    {
        $tagModel = $getTagUseCase->execute($id);

        $form = $this->createForm(TagType::class, $tagModel);
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
            'tag' => $tagModel,
        ]);
    }

    #[Route('/tag/delete/{id}', name: 'app_tag_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(int $id, Request $request, DeleteTagInterface $deleteTag): Response
    {
        if ($this->isCsrfTokenValid('delete'.$id, (string) $request->request->get('_token'))) {
            $deleteTag->execute($id);
            $this->addFlash('success', 'Le tag a été supprimé.');
        }

        return $this->redirectToRoute('app_tag_index');
    }
}
