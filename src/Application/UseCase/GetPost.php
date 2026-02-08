<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Model\PostModel;
use App\Application\Provider\PostProviderInterface;
use App\Application\UseCaseInterface\GetPostInterface;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Repository\PostRepositoryInterface;
use App\Infrastructure\MapperInterface\PostMapperInterface;

readonly class GetPost implements GetPostInterface
{
    public function __construct(
        private PostProviderInterface $postProvider
    ) {
    }

    public function execute(int $id): PostModel
    {
        return $this->postProvider->findById($id);
    }
}
