<?php
declare(strict_types=1);

namespace App\Infrastructure\Adapter;

use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\PostModel;
use App\Domain\Provider\PostProviderInterface;
use App\Infrastructure\MapperInterface\PostMapperInterface;
use App\Infrastructure\Repository\PostRepositoryInterface;
use App\Infrastructure\Repository\TagRepositoryInterface;
use App\Infrastructure\Service\PostTagSynchronizer;

readonly class PostAdapter implements PostProviderInterface
{
    public function __construct(
        private PostRepositoryInterface $postRepository,
        private TagRepositoryInterface $tagRepository,
        private PostMapperInterface     $postMapper,
        private PostTagSynchronizer     $postTagSynchronizer
    ) {
    }

    public function save(PostModel $postModel): PostModel
    {
        $post = $this->postMapper->toEntity($postModel);
        $this->postTagSynchronizer->synchronize($post, $postModel);

        $this->postRepository->save($post);
        $slug = $post->getSlug();
        if (null === $slug) {
            throw new \RuntimeException('Slug was not generated for the post.');
        }
        $postCreated = $this->postRepository->findBySlug($slug);
        if (null === $postCreated) {
            throw new \RuntimeException('Post was not found after saving.');
        }
        return $this->postMapper->toModel($postCreated);
    }

    public function delete(int $id) : void
    {

        $post = $this->postRepository->findById($id);

        if (!$post) {
            throw EntityNotFoundException::forEntity('Post', $id);
        }

        $this->postRepository->delete($post);
    }

    public function findById(int $id): PostModel
    {
        $post = $this->postRepository->findById($id);

        if (!$post) {
            throw EntityNotFoundException::forEntity('Post', $id);
        }

        return $this->postMapper->toModel($post);
    }

    public function findBySlug(string $slug): PostModel
    {
        $post = $this->postRepository->findBySlug($slug);

        if (!$post) {
            throw EntityNotFoundException::forEntity('Post', $slug);
        }

        return $this->postMapper->toModel($post);
    }

    /**
     * @return PostModel[]
     */
    public function findByTag(?int $tagId): array
    {
        if ($tagId !== null) {
            $tag = $this->tagRepository->findById($tagId);
            if (!$tag) {
                throw EntityNotFoundException::forEntity('Tag', $tagId);
            }
            $posts = $this->postRepository->findByTag($tag);
        } else {
            $posts = $this->postRepository->findAll();
        }

        return array_map(function ($post) {
            return $this->postMapper->toModel($post);
        }, $posts);
    }

    /**
     * @return PostModel[]
     */
    public function findPublished(?int $tagId = null): array
    {
        $tag = null;
        if ($tagId !== null) {
            $tag = $this->tagRepository->findById($tagId);
            if (!$tag) {
                throw EntityNotFoundException::forEntity('Tag', $tagId);
            }
        }

        $posts = $this->postRepository->findPublished($tag);

        return array_map(function ($post) {
            return $this->postMapper->toModel($post);
        }, $posts);
    }

    public function update(int $id, PostModel $postModel): PostModel
    {
        $post = $this->postRepository->findById($id);

        if (!$post) {
            throw EntityNotFoundException::forEntity('Post', $id);
        }

        $postEntity = $this->postMapper->toEntity($postModel);

        $post->setTitle($postModel->getTitle());
        $post->setContent($postModel->getContent());
        $post->setSubTitle($postModel->getSubTitle());
        $post->setPublished($postModel->isPublished());

        $this->postTagSynchronizer->synchronize($post, $postModel);

        $this->postRepository->save($post);

        return $this->postMapper->toModel($post);
    }
}
