<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapter;

use App\Domain\Criteria\PostCriteria;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\PostModel;
use App\Domain\Provider\PostProviderInterface;
use App\Infrastructure\MapperInterface\PostMapperInterface;
use App\Infrastructure\Repository\PostRepositoryInterface;
use App\Infrastructure\Service\PostTagSynchronizer;
use function array_map;

readonly class PostAdapter implements PostProviderInterface
{
    public function __construct(
        private PostRepositoryInterface $postRepository,
        private PostMapperInterface $postMapper,
        private PostTagSynchronizer $postTagSynchronizer,
    ) {}

    /**
     * @throws \RuntimeException
     */
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

    /**
     * @throws EntityNotFoundException
     */
    public function delete(int $id): void
    {
        $post = $this->postRepository->findById($id);

        if (!$post) {
            throw EntityNotFoundException::forEntity('Post', $id);
        }

        $this->postRepository->delete($post);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function findById(int $id): PostModel
    {
        $post = $this->postRepository->findById($id);

        if (!$post) {
            throw EntityNotFoundException::forEntity('Post', $id);
        }

        return $this->postMapper->toModel($post);
    }

    /**
     * @throws EntityNotFoundException
     */
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
    public function findByCriteria(PostCriteria $criteria): array
    {
        $posts = $this->postRepository->findByCriteria($criteria);

        return array_map(fn($post) => $this->postMapper->toModel($post), $posts);
    }

    /**
     * @throws EntityNotFoundException
     */
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

        $postModel->isPublished() ? $post->publish() : $post->unpublish();

        $this->postTagSynchronizer->synchronize($post, $postModel);

        $this->postRepository->save($post);

        return $this->postMapper->toModel($post);
    }
}
