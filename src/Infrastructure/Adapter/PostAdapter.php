<?php
declare(strict_types=1);

namespace App\Infrastructure\Adapter;

use App\Application\Model\PostModel;
use App\Application\Provider\PostProviderInterface;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Repository\PostRepositoryInterface;
use App\Domain\Service\PostTagSynchronizer;
use App\Infrastructure\MapperInterface\PostMapperInterface;

readonly class PostAdapter implements PostProviderInterface
{
    public function __construct(
        private PostRepositoryInterface $postRepository,
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
}
