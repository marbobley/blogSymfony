<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\DTO\TagResponseDTO;
use App\Application\Factory\TagResponseDTOFactory;
use App\Application\UseCaseInterface\GetTagBySlugInterface;
use App\Domain\Repository\TagRepositoryInterface;

class GetTagBySlug implements GetTagBySlugInterface
{
    public function __construct(
        private readonly TagRepositoryInterface $tagRepository
    ) {
    }

    public function execute(string $slug): TagResponseDTO
    {
        $tag = $this->tagRepository->findBySlug($slug);

        if (!$tag) {
            throw new \RuntimeException('Tag not found');
        }

        return TagResponseDTOFactory::createFromEntity($tag);
    }
}
