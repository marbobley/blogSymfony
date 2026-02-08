<?php
declare(strict_types=1);

namespace App\Infrastructure\Adapter;

use App\Application\Model\PostModel;
use App\Application\Provider\PostProviderInterface;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Repository\PostRepositoryInterface;
use App\Domain\Repository\TagRepositoryInterface;
use App\Domain\Service\PostTagSynchronizer;
use App\Infrastructure\MapperInterface\PostMapperInterface;

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
        $postCreated = $this->postRepository->findBySlug($post->getSlug());
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

    public function update(int $id, PostModel $postModel): PostModel
    {
        $post = $this->postRepository->findById($id);

        if (!$post) {
            throw EntityNotFoundException::forEntity('Post', $id);
        }

        $post->setTitle($postModel->getTitle());
        $post->setContent($postModel->getContent());

        $this->postTagSynchronizer->synchronize($post, $postModel);

        $this->postRepository->save($post);

        return $this->postMapper->toModel($post);
    }
}
