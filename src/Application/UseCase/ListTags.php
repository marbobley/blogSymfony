<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Factory\TagModelFactory;
use App\Application\UseCaseInterface\ListTagsInterface;
use App\Domain\Repository\TagRepositoryInterface;

class ListTags implements ListTagsInterface
{
    public function __construct(
        private readonly TagRepositoryInterface $tagRepository
    ) {
    }

    public function execute(): array
    {
        $tags = $this->tagRepository->findAll();

        return array_map(
            fn($tag) => TagModelFactory::create($tag->getId(), $tag->getName(), $tag->getSlug()),
            $tags
        );
    }
}
