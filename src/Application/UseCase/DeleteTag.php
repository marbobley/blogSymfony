<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\UseCaseInterface\DeleteTagInterface;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Repository\TagRepositoryInterface;

class DeleteTag implements DeleteTagInterface
{
    public function __construct(
        private readonly TagRepositoryInterface $tagRepository
    ) {
    }

    public function execute(int $id): void
    {
        $tag = $this->tagRepository->findById($id);

        if (!$tag) {
            throw EntityNotFoundException::forEntity('Tag', $id);
        }

        $this->tagRepository->delete($tag);
    }
}
